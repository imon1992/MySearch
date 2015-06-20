<?php

//получаем строку запроса и юрл запроса с ajaxa
//получаем массив с данными о вакансиях и делаем поиск по этим данным
include_once '../simpl/simple_html_dom.php';
include_once 'MainVacationPageParser_stackoverflow.class.php';
include_once 'ProcessingDataArrayWithText_stackoverflow.class.php';
include_once 'CacheGetter_stackoverflow.class.php';
include_once 'ParserIdFromLinks.stackoverflow.class.php';

class SearchQuery_stakoverflow {

    function search($searchTag,$searchObject) {
//        $searchObject = json_decode($searchObject);
        $mainVacationPageParser = new MainVacationPageParser_stackoverflow();
        $linksToJobsArray = $mainVacationPageParser->allLinks($searchTag);

        $parserIdFromLinks = new ParserIdFromLinks_stackoverflow();
        $idAndCompanyArray = $parserIdFromLinks->processingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_stackoverflow();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->formationMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_stackoverflow();
        $fullMapArray = $processingDataArrayWithText->takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

        $searchResultMap = $this->findKeyWords($fullMapArray, $searchObject);
        return $searchResultMap;
    }

    function findKeyWords($fullMapArray, $searchObject) {
        foreach ($fullMapArray as $idAndCompanyAndText) {

            foreach ($searchObject as $searchStringObject) {
                if($searchStringObject->search !== null) {
                    $isAllKeysPresented = $this->isKeyPresent($searchStringObject->search, $idAndCompanyAndText['text']);
                }
                if($searchStringObject->notPresented !== null){
                    $isPresentedKeyPresent = $this->isKeyPresent(
                        $searchStringObject->notPresented,
                        $idAndCompanyAndText['text']);
                }
                if ($isAllKeysPresented && !$isPresentedKeyPresent) {
                    $searchResultMap = $this->insertKeyWord($searchResultMap, $searchStringObject->name);
                }
            }
        }
        return $this->putZeroIfKeyNotPresent($searchResultMap, $searchObject );
    }

    function isKeyPresent($keyArrays, $idAndCompanyAndText) {
        foreach ($keyArrays[0] as $key=>$data) {
            $lowSearchString = $keyArrays[0]->$key;
            if (preg_match("/\b($lowSearchString)\b/i", $idAndCompanyAndText)) {
                    return true;
                }
        }
        return false;
    }

    function insertKeyWord($searchResultMap, $searchString) {
        if (null != $searchResultMap[$searchString]) {
            $searchResultMap[$searchString] ++;
        } else {
            $searchResultMap[$searchString] = 1;
        }
        return $searchResultMap;
    }

    function putZeroIfKeyNotPresent($searchResultMap, $searchObject) {
        foreach ($searchObject as $key => $searchStringObject) {
            if (null == $searchResultMap[$searchStringObject->name]) {
                $searchResultMap[$searchStringObject->name] = 0;
            }
        }
        return $searchResultMap;

    }

}

//$searchQuery = new searchQuery_stakoverflow();
//$searchResult = $searchQuery->search('[
//       {
//      "name":"symfony2",
//      "search":[
//         {
//            "name":"symfony2"
//         }
//      ],
//      "notPresented":[
//      {
//      "name":"125454",
//      "name1":"125454",
//      "name2":"125454",
//      "name3":"125454",
//      "name555":"else"
//      }
//      ]
//   },{
//   "name":"java",
//   "search":[
//   {
//   "name":"java"
//   }
//   ]
//   },{
//   "name":"javascript",
//   "search":[
//   {
//   "name":"javascript"
//   }
//   ]
//   }
//]','symfony2');
//echo'<pre>';
//print_r($searchResult);

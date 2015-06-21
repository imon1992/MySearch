<?php

include_once '../lib/simpl/simple_html_dom.php';
include_once 'MainVacationPageParser_stackoverflow.class.php';
include_once 'ProcessingDataArrayWithText_stackoverflow.class.php';
include_once 'CacheGetter_stackoverflow.class.php';
include_once 'ParserIdFromLinks.stackoverflow.class.php';
include_once '../Common.class.php';

class SearchQuery_stakoverflow
{

   public function search($searchTag, $searchObject)
    {
//        $searchObject = json_decode($searchObject);
        $mainVacationPageParser = new MainVacationPageParser_stackoverflow();
        $linksToJobsArray = $mainVacationPageParser->allLinks($searchTag);

        $parserIdFromLinks = new ParserIdFromLinks_stackoverflow();
        $idAndCompanyArray = $parserIdFromLinks->processingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_stackoverflow();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->formationMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_stackoverflow();
        $fullMapArray = $processingDataArrayWithText->takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);
       $common = new Common();
        $searchResultMap = $common->findKeyWords($fullMapArray, $searchObject);
        return $searchResultMap;
    }

}


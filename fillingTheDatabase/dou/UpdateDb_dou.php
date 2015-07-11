<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
include_once DOCUMENT_ROOT.'/Search/abstractClass/SearchQuery.php';
include_once 'ProcessingDataArrayWithText_dou.php';
include_once 'MainVacationPageParser_dou.php';
include_once 'CacheGetter_dou.php';
include_once 'ParseDataFromLinks_dou.php';

class UpdateDb_dou
{
     function updateDb($searchTag)
    {
        $mainVacationPageParser = new MainVacationPageParser_dou();
        $linksToJobsArray = $mainVacationPageParser->getAllLinks($searchTag);
//var_dump($linksToJobsArray);
        $parserIdAndCompanyFromLinks = new ParseDataFromLinks_dou();
        $idAndCompanyArray = $parserIdAndCompanyFromLinks->getProcessingReferences($linksToJobsArray);
//var_dump($idAndCompanyArray);
        $cacheGetter = new CacheGetter_dou();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);
//var_dump($idAndCompaniesAndMayNotBeCompleteTextArray);
        $processingDataArrayWithText = new ProcessingDataArrayWithText_dou();
        $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

//        echo 'Обновление базы успешно завершено ';

    }
}

//$c = new SearchQuery_dou();
//$c->searchByDate();
//$searchParams = json_decode('[{"searchTag":"PHP","site":"?dou","city":"Николаев"},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]');
//$searchParams = json_decode('[{"searchTag":"php","site":"?stackoverflow","date":{"from":"01-06-2015","by":"08-07-2015"}},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]');
//$whereAndWhatSearchObject = array_shift($searchParams);
//$whereAndWhatSearchObjectLength = sizeof($whereAndWhatSearchObject);

//if ($whereAndWhatSearchObject->site == '?dou'){
//    $searchQuery = new SearchQuery_douAddToDb();
//    $searchResponse = $searchQuery->updateDb('php');
//    $searchResponse = json_encode($searchResponse);
//    echo $searchResponse;
//}

//$x = $c->getSearch($json,'');
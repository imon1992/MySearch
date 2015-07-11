<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
//include_once '../abstractClass/SearchQuery.php';
include_once 'MainVacationPageParser_stackoverflow.php';
include_once 'ParseDataFromLinks_stackoverflow.php';
include_once 'CacheGetter_stackoverflow.php';
include_once 'ProcessingDataArrayWithText_stackoverflow.php';

class UpdateDb_stackoverflow
{
     function updateDb($searchTag)
    {
        $mainVacationPageParser = new MainVacationPageParser_stackoverflow();
        $linksToJobsArray = $mainVacationPageParser->getAllLinks($searchTag);

        $parserIdFromLinks = new ParseDataFromLinks_stackoverflow();
        $idAndCompanyArray = $parserIdFromLinks->getProcessingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_stackoverflow();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_stackoverflow();
        $fullMapArray = $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

echo 'Exelent';
    }
}

//$c = new SearchQuery_stackoverflowAddToDb();
//$c->updateDb('symfony2');
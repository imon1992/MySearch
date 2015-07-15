<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
include_once 'MainVacationPageParser_rabota.php';
include_once 'ParseDataFromLinks_rabota.php';
include_once 'CacheGetter_rabota.php';
include_once 'ProcessingDataArrayWithText_rabota.php';

class UpdateDb_rabota{
     function updateDb($searchTag){
        $mainVacationPageParser = new MainVacationPageParser_rabota();
        $linksToJobsArray = $mainVacationPageParser->getAllLinks($searchTag);

        $parserIdFromLinks = new ParseDataFromLinks_rabota();
        $idAndCompanyArray = $parserIdFromLinks->getProcessingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_rabota();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_rabota();
        $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);
    }
}
<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/simpl/simple_html_dom.php';
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
        $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

    }
}
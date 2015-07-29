<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/simpl/simple_html_dom.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/abstractClass/SearchQuery.php';
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

        $parserIdAndCompanyFromLinks = new ParseDataFromLinks_dou();
        $idAndCompanyArray = $parserIdAndCompanyFromLinks->getProcessingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_dou();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_dou();
        $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);
    }
}

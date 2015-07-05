<?php
include_once '../lib/simpl/simple_html_dom.php';
include_once '../abstractClass/SearchQuery.php';
include_once 'MainVacationPageParser_stackoverflow.php';
include_once 'ParseDataFromLinks_stackoverflow.php';
include_once 'CacheGetter_stackoverflow.php';
include_once 'ProcessingDataArrayWithText_stackoverflow.php';

class SearchQuery_stackoverflow extends SearchQuery
{
    protected function search($searchTag, $searchObject)
    {
        $mainVacationPageParser = new MainVacationPageParser_stackoverflow();
        $linksToJobsArray = $mainVacationPageParser->getAllLinks($searchTag);

        $parserIdFromLinks = new ParseDataFromLinks_stackoverflow();
        $idAndCompanyArray = $parserIdFromLinks->getProcessingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_stackoverflow();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_stackoverflow();
        $fullMapArray = $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

        return parent::findKeyWords($fullMapArray, $searchObject);
    }
}

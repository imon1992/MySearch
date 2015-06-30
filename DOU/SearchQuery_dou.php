<?php
include_once '../lib/simpl/simple_html_dom.php';
include_once '../abstractClass/SearchQuery.php';
include_once 'ProcessingDataArrayWithText_dou.php';
include_once 'MainVacationPageParser_dou.php';
include_once 'CacheGetter_dou.php';
include_once 'ParseDataFromLinks_dou.php';

class SearchQuery_dou extends SearchQuery
{
    protected function search($searchTagAndCity, $searchObject)
    {
        $mainVacationPageParser = new MainVacationPageParser_dou();
        $linksToJobsArray = $mainVacationPageParser->getAllLinks($searchTagAndCity);

        $parserIdAndCompanyFromLinks = new ParseDataFromLinks_dou();
        $idAndCompanyArray = $parserIdAndCompanyFromLinks->getProcessingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_dou();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->getMapWithText($idAndCompanyArray);
        $processingDataArrayWithText = new ProcessingDataArrayWithText_dou();
        $fullMapArray = $processingDataArrayWithText->getTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

        return parent::findKeyWords($fullMapArray, $searchObject);
    }
}
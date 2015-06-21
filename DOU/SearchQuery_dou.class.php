<?php


include_once '../lib/simpl/simple_html_dom.php';
include_once 'ProcessingDataArrayWithText_dou.class.php';
include_once 'MainVacationPageParser_dou.class.php';
include_once 'CacheGetter_dou.class.php';
include_once 'ParserIdAndCompanyFromLinks_dou.class.php';
include_once '../Common.class.php';

class SearchQuery_dou
{

    function search($searchTagAndCity, $searchObject)
    {

        $mainVacationPageParser = new MainVacationPageParser_dou();
        $linksToJobsArray = $mainVacationPageParser->parseNextPart($searchTagAndCity);

        $parserIdAndCompanyFromLinks = new ParserIdAndCompanyFromLinks_dou();
        $idAndCompanyArray = $parserIdAndCompanyFromLinks->processingReferences($linksToJobsArray);

        $cacheGetter = new CacheGetter_dou();
        $idAndCompaniesAndMayNotBeCompleteTextArray = $cacheGetter->formationMapWithText($idAndCompanyArray);

        $processingDataArrayWithText = new ProcessingDataArrayWithText_dou();
        $fullMapArray = $processingDataArrayWithText->takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray);

$common = new Common();
        $searchResultMap = $common->findKeyWords($fullMapArray, $searchObject);
        return $searchResultMap;
    }



}


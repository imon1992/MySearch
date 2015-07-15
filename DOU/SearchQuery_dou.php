<?php
include_once '../lib/simpl/simple_html_dom.php';
include_once '../abstractClass/SearchQuery.php';
include_once '../general/ProcessingVacanciesInfo.php';
include_once 'ProcessingWithDate_dou.php';
include_once '../general/ProcessingWithCity.php';

class SearchQuery_dou extends SearchQuery
{
    protected function search($searchTagCityAndDate, $searchObject)
    {

    $generateDateParams = new GenerateDataParams_dou();
        $dateFromToBy = $generateDateParams->generateDateInfo($searchTagCityAndDate);

        if($dateFromToBy['error']){
            return $dateFromToBy['errorText'];
        }

        $processingVacanciesInfo = new ProcessingVacanciesInfo_dou();
        $vacanciesMap = $processingVacanciesInfo->getVacanciesInfo($dateFromToBy,__CLASS__,$searchTagCityAndDate);

        return parent::findKeyWords($vacanciesMap, $searchObject);
    }
}
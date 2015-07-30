<?php
include_once '../lib/simpl/simple_html_dom.php';
include_once '../abstractClass/SearchQuery.php';
include_once '../general/ProcessingVacanciesInfo.php';
include_once 'ProcessingWithDate_stackoverflow.php';
include_once '../general/ProcessingWithCity.php';

class SearchQuery_stackoverflow extends SearchQuery
{
    protected function search($searchTagCityAndDate, $searchObject)
    {
        $generateDateParams = new ProcessingWithDate_stackoverflow();
        $dateFromToBy = $generateDateParams->generateDateInfo($searchTagCityAndDate);

        if(array_key_exists('error',$dateFromToBy)){
            return $dateFromToBy['errorText'];
        }
        $processingVacanciesInfo = new ProcessingVacanciesInfo();
        $vacanciesMap = $processingVacanciesInfo->getVacanciesInfo($dateFromToBy,__CLASS__,$searchTagCityAndDate);

        return parent::findKeyWords($vacanciesMap, $searchObject);
    }
}


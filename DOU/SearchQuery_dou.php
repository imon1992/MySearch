<?php
include_once '../lib/simpl/simple_html_dom.php';
include_once '../abstractClass/SearchQuery.php';
//include_once 'ProcessingDataArrayWithText_dou.php';
//include_once 'MainVacationPageParser_dou.php';
//include_once 'CacheGetter_dou.php';
//include_once 'ParseDataFromLinks_dou.php';
include_once '../general/ProcessingVacanciesInfo.php';
include_once 'ProcessingWithDate_dou.php';
include_once '../general/ProcessingWithCity.php';

class SearchQuery_dou extends SearchQuery
{
    protected function search($searchTagCityAndDate, $searchObject)
    {
//        $processingWithCity = new ProcessingWithCity();
//        $city = $processingWithCity->generateCity($searchTagCityAndDate);

    $generateDateParams = new GenerateDataParams_dou();
        $dateFromToBy = $generateDateParams->generateDateInfo($searchTagCityAndDate);
var_dump($dateFromToBy);
        if($dateFromToBy['error']){
            return $dateFromToBy['errorText'];
        }

        $processingVacanciesInfo = new ProcessingVacanciesInfo_dou();
        $vacanciesMap = $processingVacanciesInfo->getVacanciesInfo($dateFromToBy,__CLASS__,$searchTagCityAndDate);
var_dump($vacanciesMap);
        return parent::findKeyWords($vacanciesMap, $searchObject);
    }
}

//$c = new SearchQuery_dou();
//$c->getSearch(json_decode('[{"searchTag":"PHP","site":"?dou","city":"","date":{"from":"03-05-2015","by":"09-07-2015"}}]'),'');
//[{"searchTag":"PHP","site":"?dou","city":"","date":{"from":"03-07-2015","by":"09-07-2015"}}]
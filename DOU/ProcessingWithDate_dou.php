<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/DOU/CurlInit_Dou.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/general/GenerateUrl.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/general/ProcessingWithCity.php';

class GenerateDataParams_dou
{
    public function newFormatDateAsRussianMonth($date)
    {
        $date = trim($date);
        $date_elements  = explode(" ",$date);
        $month = str_replace(array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'),
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            $date_elements[1]);
        $date = $date_elements[0] . ' ' . $month  . ' ' . $date_elements[2];
//        $date = trim($date);
        return date("Y.m.d", strtotime($date));
    }

    public function newFormatDateMonthAsNumber($date)
    {
        $date_elements  = explode("-",$date);
//        $date_elements[0] ='число';
//        $date_elements[1] ='месяц';
//        $date_elements[2] = 'год';

        $month = str_replace(array('01','02','03','04','05','06','07','08','09','10','11','12'),
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            $date_elements[1]);
        $date = $date_elements[0] . ' ' . $month  . ' ' . $date_elements[2];

        $date = trim($date);
        return date("Y.m.d", strtotime($date));
    }

    public function generateDateInfo($searchTagCityAndDate)
    {

        $city = $searchTagCityAndDate->city;

        $searchTag = $searchTagCityAndDate->searchTag;

        if (!property_exists($searchTagCityAndDate,'date')){
            $by = date("Y.m.d");
            $from = $this->getDateLastAddition($city,$searchTag);
            $from = $this->newFormatDateAsRussianMonth($from);

        }else{
            $datePartBy = explode('-',$searchTagCityAndDate->date->by);
            $datePartFrom = explode('-',$searchTagCityAndDate->date->from);

            if(checkdate($datePartBy[1],$datePartBy[0],$datePartBy[2])&&checkdate($datePartFrom[1],$datePartFrom[0],$datePartFrom[2])){
                $by = $this->newFormatDateMonthAsNumber($searchTagCityAndDate->date->by);
                $from = $this->newFormatDateMonthAsNumber($searchTagCityAndDate->date->from);
            }else{
                return ['errorText'=>'Неверный формат даты','error'=>true];
            }
        }

        $dateInfo = ['by'=> $by,'from'=>$from];
        return $dateInfo;
    }

    function getDateLastAddition($city, $searchTag)
    {
        $generateUrl = new GenerateUrl();
        $url = $generateUrl->generateUrlFirstPageDou($searchTag,$city);
        $curlInit = new CurlInit_Dou();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        foreach ($html->find('div.b-vacancies-head h1') as $element) {
            $countOfVacationsWithExcessText = $element->innertext;
        }

        preg_match("/\d+/", $countOfVacationsWithExcessText, $countOfVacationsArray);
        $countOfVacations = $countOfVacationsArray[0];

        if($countOfVacations > 20) {
            $url = $generateUrl->generateUrlNextPart($city, $searchTag);
        $curlResult = $curlInit->getCurlInit($url-1);
        }else {
            $curlResult = $curlInit->getCurlInit($url);
        }
        preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $curlResult, $lastLinkToJob);

        $url = array_pop($lastLinkToJob[0]);

        $curlResult = $curlInit->getCurlInit($url);

        $html->load($curlResult);

        $date = $html->find('div[class=date]');

        $dateLastVacancy = $date[0]->innertext;

        $dateLastVacancy = trim($dateLastVacancy);
        return $dateLastVacancy;
    }
}


<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/Search/lib/simpl/simple_html_dom.php';
include_once 'CurlInit_stackoverflow.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/Search/general/GenerateUrl.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/Search/general/ProcessingWithCity.php';

class ProcessingWithDate_stackoverflow{
    function dateInfo($timeSpan){
        $daysOrWeeks='';
        $timeInterval='';
        if(strpos($timeSpan, 'yesterday')){
            $timeInterval = 'DAY';
            $daysOrWeeks = 1;
        }elseif(strpos($timeSpan, 'days')){
            $timeInterval = 'DAY';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }elseif(strpos($timeSpan, 'week')||strpos($timeSpan, 'weeks')){
            $timeInterval = 'WEEK';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }else{
            $timeInterval = 'now';
        }
        return [$timeInterval,$daysOrWeeks];
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

    function newFormatDate($timeSpan)
    {

        $dateInfo = $this->dateInfo($timeSpan);
        $timeInterval = $dateInfo[0];
        if ($timeInterval != 'now') {

        $d = new DateTime();
        $d->modify("-" . $dateInfo[1] . "$dateInfo[0] ");
        $dateLastAdd = $d->format("Y-m-d");
    }else{
            $dateLastAdd = date('Y-m-d');
        }
        return $dateLastAdd;
    }

    public function generateDateInfo($searchTagCityAndDate)
    {

        $city = $searchTagCityAndDate->city;
        $searchTag = $searchTagCityAndDate->searchTag;

        if ($searchTagCityAndDate->date == null) {
            $by = date("Y.m.d");
            $from = $this->getDateLastAddition($city,$searchTag);
            $from = $this->newFormatDate($from);
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

    function getDateLastAddition($city,$searchTag)
    {
        $generateUrl = new GenerateUrl();
        $url = $generateUrl->generateUrlFirstPageStackoverflow($city,$searchTag);

        $curlInit = new CurlInit_stackoverflow();
        $curlResult = $curlInit->getCurlInit($url);
        $html = new simple_html_dom();
        $html->load($curlResult);

        foreach ($html->find('#index-hed h2 span') as $element) {
            $countOfVacationsWithExcessText = $element->innertext;
        }

        preg_match("/\d+/", $countOfVacationsWithExcessText, $countOfVacationsArray);
        $countOfVacations = $countOfVacationsArray[0];
        $countOfPages = ceil($countOfVacations / 25);
        $url = $generateUrl->generateUrlLastPageStackoverflow($city,$searchTag,$countOfPages);
        $curlResult = $curlInit->getCurlInit($url);

        $html->load($curlResult);
        foreach ($html->find('div[class=listResults -jobs list jobs]') as $element) {

            foreach ($element->find('div.listResults div.tags') as $tagsName) {
                    $dateAddAllVacationOnPage[] = $tagsName->parentNode()->childNodes(0)->innertext;
            }

        }
        $dateLastVacancy = array_pop($dateAddAllVacationOnPage);

        return $dateLastVacancy;
    }

}

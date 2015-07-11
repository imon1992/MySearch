<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
include_once 'CurlInit_rabota.php';
include_once DOCUMENT_ROOT.'/Search/general/GenerateUrl.php';
include_once DOCUMENT_ROOT.'/Search/general/ProcessingWithCity.php';

class ProcessingWithDate_rabota
{
    function dateInfo($timeSpan)
    {
        if (strpos($timeSpan, 'часов')) {
            $timeInterval = 'now';
        } elseif (strpos($timeSpan, 'день')) {
            $timeInterval = 'DAY';
            $daysOrWeeks = 1;
        } elseif (strpos($timeSpan, 'дней') || strpos($timeSpan, 'дня')) {
            $timeInterval = 'DAY';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks = $dayORWeeksArray[0];
        } elseif (strpos($timeSpan, 'неделю')) {
            $timeInterval = 'WEEK';
            $daysOrWeeks = 1;
        } elseif (strpos($timeSpan, 'недели')) {
            $timeInterval = 'WEEK';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks = $dayORWeeksArray[0];
        }elseif(strpos($timeSpan, 'месяц')){
            $timeInterval = 'month';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks = $dayORWeeksArray[0];
        }else {
            $timeInterval = 'now';
        }
        return [$timeInterval, $daysOrWeeks];
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
//        var_dump($date);
        $date = trim($date);
        return date("Y.m.d", strtotime($date));
    }


    function newFormatDate($timeSpan){
        $dateInfo = $this->dateInfo($timeSpan);
        $timeInterval = $dateInfo[0];
        if ($timeInterval != 'now') {

            $d = new DateTime();
            $d->modify("-" . $dateInfo[1] . "$dateInfo[0] ");
            $dateLastAdd = $d->format("Y-m-d");
        }else{
            $dateLastAdd = date('Y-m-d');
        }
//        var_dump($dateLastAdd);
        return $dateLastAdd;
    }

    public function generateDateInfo($searchTagCityAndDate)
    {
//        var_dump($searchTagCityAndDate);
//        var_dump($searchTagCityAndDate);
        $processingWithCity = new ProcessingWithCity();
        $city = $processingWithCity->generateCity($searchTagCityAndDate);
//var_dump($city);
        $searchTag = $searchTagCityAndDate->searchTag;
//        var_dump($searchTag);
//var_dump($searchTagCityAndDate);
        if ($searchTagCityAndDate->date == null) {
            $by = date("Y.m.d");
            $from = $this->getDateLastAddition($searchTag);
            $from = $this->newFormatDate($from);
//            var_dump($from);
        }else{
            $datePartBy = explode('-',$searchTagCityAndDate->date->by);
            $datePartFrom = explode('-',$searchTagCityAndDate->date->from);
//            var_dump(checkdate($datePart[1],$datePart[0],$datePart[2]));
            if(checkdate($datePartBy[1],$datePartBy[0],$datePartBy[2])&&checkdate($datePartFrom[1],$datePartFrom[0],$datePartFrom[2])){
                $by = $this->newFormatDateMonthAsNumber($searchTagCityAndDate->date->by);
                $from = $this->newFormatDateMonthAsNumber($searchTagCityAndDate->date->from);
            }else{
                return ['errorText'=>'Неверный формат даты','error'=>true];
            }
        }
//        var_dump($from);
//        var_dump($by);
//        var_dump($from);
        $dateInfo = ['by'=> $by,'from'=>$from];
//        var_dump($dateInfo);
        return $dateInfo;
    }

    function getDateLastAddition($searchTag)
    {
//        var_dump()
        $generateUrl = new GenerateUrl();
        $url = $generateUrl->generateUrlFirstPageRabota($searchTag);

        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);
//var_dump($url);
        $html = new simple_html_dom();
        $html->load($curlResult);
        foreach ($html->find('#beforeContentZone_vacancyList_ltCount') as $element) {
            $countOfVacationsWithExcessText = $element->innertext;
        }

        preg_match("/\d+/", $countOfVacationsWithExcessText, $countOfVacationsArray);
        $countOfVacations = $countOfVacationsArray[0];
//        var_dump($countOfVacations);
        $countOfPages = ceil($countOfVacations / 20);
//var_dump($countOfPages);
        $url = $generateUrl->generateUrlLastPageRabota($searchTag,$countOfPages);
//var_dump($countOfVacations);
        $curlResult = $curlInit->getCurlInit($url);

        $html->load($curlResult);
//echo $html;
        foreach ($html->find('table.vv tbody ') as $element) {
            foreach($html->find('div.dt') as $element){
                $dateAddAllVacationOnPage[] = $element->innertext;
            }
        }
        $dateLastVacancy = array_pop($dateAddAllVacationOnPage);
//        var_dump($dateLastVacancy);
//        $dateLastVacancy = $this->newFormatDate($dateLastVacancy);
//        var_dump($dateLastVacancy);

        return $dateLastVacancy;
    }
}
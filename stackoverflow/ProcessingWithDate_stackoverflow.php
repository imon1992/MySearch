<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
include_once 'CurlInit_stackoverflow.php';
include_once DOCUMENT_ROOT.'/Search/general/GenerateUrl.php';
include_once DOCUMENT_ROOT.'/Search/general/ProcessingWithCity.php';

class ProcessingWithDate_stackoverflow{
    function dateInfo($timeSpan){
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
//        var_dump($date);
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
//            $str = '05-12-2000';
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
        $url = $generateUrl->generateUrlFirstPageStackoverflow($searchTag);

        $curlInit = new CurlInit_stackoverflow();
        $curlResult = $curlInit->getCurlInit($url);
//var_dump($url);
        $html = new simple_html_dom();
        $html->load($curlResult);

        foreach ($html->find('#index-hed h2 span') as $element) {
            $countOfVacationsWithExcessText = $element->innertext;
        }

        preg_match("/\d+/", $countOfVacationsWithExcessText, $countOfVacationsArray);
        $countOfVacations = $countOfVacationsArray[0];
//        var_dump($countOfVacations);
        $countOfPages = ceil($countOfVacations / 25);
//var_dump($countOfPages);
        $url = $generateUrl->generateUrlLastPageStackoverflow($searchTag,$countOfPages);
//var_dump($countOfVacations);
        $curlResult = $curlInit->getCurlInit($url);

        $html->load($curlResult);
//echo $html;
        foreach ($html->find('div[class=listResults -jobs list jobs]') as $element) {

            foreach ($element->find('div.listResults div.tags') as $tagsName) {
                    $dateAddAllVacationOnPage[] = $tagsName->parentNode()->childNodes(0)->innertext;
            }

        }
        $dateLastVacancy = array_pop($dateAddAllVacationOnPage);
//        $dateLastVacancy = $this->newFormatDate($dateLastVacancy);
//        var_dump($dateLastVacancy);

        return $dateLastVacancy;
    }

}

//$c = new GenerateDateInfo_stackoverflow();
//$c->getDateLastAddition('php');
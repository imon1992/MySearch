<?php
//header('Content-type: text/html');
//header("Content-Type: text/html; charset=utf-8");
//include_once '../lib/simpl/simple_html_dom.php';
//include_once '../abstractClass/ProcessingWithDate.php';
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/DOU/CurlInit_Dou.php';
include_once DOCUMENT_ROOT.'/Search/general/GenerateUrl.php';
include_once DOCUMENT_ROOT.'/Search/general/ProcessingWithCity.php';

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
//        var_dump($date);
        $date = trim($date);
        return date("Y.m.d", strtotime($date));
    }

    public function generateDateInfo($searchTagCityAndDate)
    {
//        $processingWithCity = new ProcessingWithCity();
        $city = $searchTagCityAndDate->city;

        $searchTag = $searchTagCityAndDate->searchTag;
//var_dump($searchTagCityAndDate);
        if ($searchTagCityAndDate->date == null) {
            $by = date("Y.m.d");
            $from = $this->getDateLastAddition($city,$searchTag);
            $from = $this->newFormatDateAsRussianMonth($from);
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
        return $dateInfo;
    }

    function getDateLastAddition($city, $searchTag)
    {
        $generateUrl = new GenerateUrl();
        $url = $generateUrl->generateUrlFirstPageDou($searchTag,$city);
        $curlInit = new CurlInit_Dou();
        $curlResult = $curlInit->getCurlInit($url);
//var_dump($url);
        $html = new simple_html_dom();
        $html->load($curlResult);

        foreach ($html->find('div.b-vacancies-head h1') as $element) {
            $countOfVacationsWithExcessText = $element->innertext;
        }

        preg_match("/\d+/", $countOfVacationsWithExcessText, $countOfVacationsArray);
        $countOfVacations = $countOfVacationsArray[0];
//var_dump($countOfVacations);
        if($countOfVacations > 20) {
            $url = $generateUrl->generateUrlNextPart($city, $searchTag);
        $curlResult = $curlInit->getCurlInit($url-1);
        }else {
            $curlResult = $curlInit->getCurlInit($url);
        }
        preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $curlResult, $lastLinkToJob);
//var_dump($lastLinkToJob);
        $url = array_pop($lastLinkToJob[0]);
//var_dump($url);
        $curlResult = $curlInit->getCurlInit($url);

        $html->load($curlResult);

        $date = $html->find('div[class=date]');

        $dateLastVacancy = $date[0]->innertext;
//        var_dump($dateLastVacancy);
        $dateLastVacancy = trim($dateLastVacancy);
        return $dateLastVacancy;
    }
}

//$c = new GenerateDataParams_dou();
////$x = $c->generateDateIfEmpty('');
//$x = $c->newFormatDateMonthAsNumber('09-07-2015');
//echo $x;
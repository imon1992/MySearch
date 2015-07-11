<?php
header("Content-Type: text/html; charset=utf-8");
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/MainVacationPageParser.php';
include_once DOCUMENT_ROOT.'/Search/rabota/CurlInit_rabota.php';
include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';
include_once DOCUMENT_ROOT.'/Search/general/ProcessingWithCity.php';
class MainVacationPageParser_rabota extends MainVacationPageParser
{
    private function linksParse($url)
    {
        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        $processingWithCity = new ProcessingWithCity();

        $fullLinksToJobs = array();
        foreach ($html->find('table.vv tbody ') as $element) {
            foreach($element->find('div[class=rua-g-clearfix] a.t')as $link) {
                $partLinksToJob[] =  $link->href;
            }
            foreach($html->find('div.dt') as $element){
                $dateAdd[] = $element->innertext;
            }
        }
        foreach($html->find('div[class=rua-g-clearfix] div.s')as $city) {
//            echo $city->innertext;
            $clearCity = $processingWithCity->parseCityFromStringRabota($city->innertext);
            $cities[] =  $clearCity;

        }

        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $key=>$linksPart) {

                $fullLinksToJobs[] = array('linkToJob'=>'http://rabota.ua/' . $linksPart,'dateAdd'=>$dateAdd[$key],'city'=>$cities[$key]);
            }
        }
//        var_dump($fullLinksToJobs);
        return $fullLinksToJobs;
    }

    function generateAllLinks($searchTag)
    {
        $searchTag = parent::changSumSymbols($searchTag);
        $url = 'http://rabota.ua/jobsearch/vacancy_list?keywords=' . $searchTag;

        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        foreach ($html->find('#beforeContentZone_vacancyList_ltCount') as $element) {
            preg_match("/\d+/", $element->innertext, $countOfVacancy);
        }
        $countOfVacancy = $countOfVacancy[0];
        $countOfPages = ceil($countOfVacancy / 20);
        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            $linksToJob = $this->linksParse($urlWithPageNumber);
            if ($linksToJob != null && is_array($linksToJob))
                $allLinksToJob = array_merge((array)$allLinksToJob, $linksToJob);
        }
        array_push($allLinksToJob,$searchTag);
//        var_dump($linksToJob);
        return $allLinksToJob;

    }
}
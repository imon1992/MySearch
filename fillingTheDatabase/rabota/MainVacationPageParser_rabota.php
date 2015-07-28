<?php
header("Content-Type: text/html; charset=utf-8");
//define("$_SERVER['DOCUMENT_ROOT']", $_SERVER['$_SERVER['DOCUMENT_ROOT']']);
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/abstractClass/MainVacationPageParser.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/rabota/CurlInit_rabota.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/lib/simpl/simple_html_dom.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/general/ProcessingWithCity.php';

class MainVacationPageParser_rabota extends MainVacationPageParser
{
    private function linksParse($url,$iterationCount=20)
    {
        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        $processingWithCity = new ProcessingWithCity();

        $fullLinksToJobDateAddCityAndTags = array();
        $table = $html->find('table.vv tbody ')[0];

        for ($i = 0; $i < $iterationCount; $i++) {
            $dateAdd[$i] = '';

            foreach ($table->childNodes($i)->find('div.dt') as $el) {
                $dateAdd[$i] = $el->innertext;
            }

        }

        for ($i = 0; $i < $iterationCount; $i++) {
        $tags[$i] = array();

            foreach ($table->childNodes($i)->find('div.tags a') as $key => $elements) {
                $tags[$i][$key] = $elements->innertext;
            }

        }
        for ($i = 0; $i < $iterationCount; $i++) {
        $partLinksToJob[$i]='';

            foreach ($table->childNodes($i)->find('div[class=rua-g-clearfix] a.t') as $link) {
                $partLinksToJob[$i] = $link->href;
            }

        }
        $cities = array();
        for ($i = 0; $i < $iterationCount; $i++) {

            foreach ($table->childNodes($i)->find('div[class=rua-g-clearfix] div.s') as $city) {
                $clearCity = $processingWithCity->parseCityFromStringRabota($city->innertext);
                $cities[$i] = $clearCity;
            }

        }

        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $key => $linksPart) {

                $fullLinksToJobDateAddCityAndTags[] = array('linkToJob' => 'http://rabota.ua' . $linksPart,
                    'dateAdd' => $dateAdd[$key],
                    'city' => $cities[$key],
                    'tags' => $tags[$key]);
            }
        }

        return $fullLinksToJobDateAddCityAndTags;
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
        $lastPageCount = $countOfVacancy%20;

        $countOfPages = ceil($countOfVacancy / 20);
        $allLinksToJobDateAddCityAndTags = array();
        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            if($i!=$countOfPages) {
                $linksToJobDateAddCityAndTags = $this->linksParse($urlWithPageNumber);
            }else{
                $linksToJobDateAddCityAndTags = $this->linksParse($urlWithPageNumber, $lastPageCount);
            }
            if ($linksToJobDateAddCityAndTags != null && is_array($linksToJobDateAddCityAndTags))
                $allLinksToJobDateAddCityAndTags = array_merge($allLinksToJobDateAddCityAndTags, $linksToJobDateAddCityAndTags);
        }
        array_push($allLinksToJobDateAddCityAndTags, $searchTag);

        return $allLinksToJobDateAddCityAndTags;

    }
}
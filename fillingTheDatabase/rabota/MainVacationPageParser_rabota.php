<?php
header("Content-Type: text/html; charset=utf-8");
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT . '/Search/abstractClass/MainVacationPageParser.php';
include_once DOCUMENT_ROOT . '/Search/rabota/CurlInit_rabota.php';
include_once DOCUMENT_ROOT . '/Search/lib/simpl/simple_html_dom.php';
include_once DOCUMENT_ROOT . '/Search/general/ProcessingWithCity.php';

class MainVacationPageParser_rabota extends MainVacationPageParser
{
    private function linksParse($url)
    {
        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        $processingWithCity = new ProcessingWithCity();

        $fullLinksToJobDateAddCityAndTags = array();
        foreach ($html->find('table.vv tbody ') as $element) {

            foreach ($element->find('div.tags') as $key => $link) {

                foreach ($link->find('a') as $val) {
                    if ($key != $check)
                        $tags[$key] = array();

                    $tags[$key][] = $val->innertext;
                    $check = $key;

                }
            }

            foreach ($element->find('div[class=rua-g-clearfix] a.t') as $link) {
                $partLinksToJob[] = $link->href;
            }

            foreach ($html->find('div.dt') as $element) {
                $dateAdd[] = $element->innertext;
            }
        }

        foreach ($html->find('div[class=rua-g-clearfix] div.s') as $city) {
            $clearCity = $processingWithCity->parseCityFromStringRabota($city->innertext);
            $cities[] = $clearCity;

        }

        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $key => $linksPart) {

                $fullLinksToJobDateAddCityAndTags[] = array('linkToJob' => 'http://rabota.ua/' . $linksPart,
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
        $countOfPages = ceil($countOfVacancy / 20);
        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            $linksToJobDateAddCityAndTags = $this->linksParse($urlWithPageNumber);
            if ($linksToJobDateAddCityAndTags != null && is_array($linksToJobDateAddCityAndTags))
                $allLinksToJobDateAddCityAndTags = array_merge((array)$allLinksToJobDateAddCityAndTags, $linksToJobDateAddCityAndTags);
        }
        array_push($allLinksToJobDateAddCityAndTags,$searchTag);
        return $allLinksToJobDateAddCityAndTags;

    }
}
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/abstractClass/MainVacationPageParser.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/stackoverflow/CurlInit_stackoverflow.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/lib/simpl/simple_html_dom.php';


class MainVacationPageParser_stackoverflow extends MainVacationPageParser
{
    private function linksParse($url, $iterationCount = 25)
    {
        $curlInit = new CurlInit_stackoverflow();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        $linksToJobDateAddAndTags = array();


        $table = $html->find('div[class=listResults -jobs list jobs] div.listResults')[0];

        for ($i = 0; $i < $iterationCount; $i++) {
            $partLinksToJob[$i] = '';
            $dateAdd[$i] = '';
            $tags[$i] = array();
            foreach ($table->childNodes($i)->find('h3[class=-title] a') as $link) {
                $partLinksToJob[$i] = $link->href;
            }
            foreach ($table->childNodes($i)->find('p[class=text _small _muted posted top]') as $date) {
                $dateAdd[$i] = $date->innertext;
            }
            foreach ($table->childNodes($i)->find('div.tags a') as $key => $elements) {
                $tags[$i][$key] = $elements->innertext;
            }
        }

        $linksToJobDateAddAndTags = [];
        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $key => $linksPart) {

                $linksToJobDateAddAndTags[] = array('linkToJob' => 'http://careers.stackoverflow.com' . $linksPart,
                    'dateAdd' => $dateAdd[$key],
                    'tags' => $tags[$key]);
            }
        }
        return $linksToJobDateAddAndTags;
    }

    protected function generateAllLinks($searchTag)
    {
        $searchTag = parent::changSumSymbols($searchTag);
        $url = 'http://careers.stackoverflow.com/jobs?searchTerm=' . $searchTag;
        $html = file_get_html($url);

        foreach ($html->find('#index-hed h2 span') as $element) {
            preg_match("/\d+/", $element->innertext, $countOfVacancy);
        }

        $countOfVacancy = $countOfVacancy[0];
        $countOfPages = ceil($countOfVacancy / 25);
        $lastPageCount = $countOfVacancy % 25;
        $allLinksToJobDateAddAndTags = array();

        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            if ($i != $countOfPages) {
                $linksToJob = $this->linksParse($urlWithPageNumber);
            } else {
                $linksToJob = $this->linksParse($urlWithPageNumber, $lastPageCount);
            }

            if ($linksToJob != null && is_array($linksToJob))
                $allLinksToJobDateAddAndTags = array_merge((array)$allLinksToJobDateAddAndTags, $linksToJob);
        }
        array_push($allLinksToJobDateAddAndTags, $searchTag);
        return $allLinksToJobDateAddAndTags;

    }
}
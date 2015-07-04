<?php
header("Content-Type: text/html; charset=utf-8");
include_once '../abstractClass/MainVacationPageParser.php';
include_once 'CurlInit_rabota.php';
include_once '../lib/simpl/simple_html_dom.php';
class MainVacationPageParser_rabota extends MainVacationPageParser
{
    private function linksParse($url)
    {
        $curlInit = new CurlInit_rabota();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);
        $fullLinksToJobs = array('linksToJob' => array());
        foreach ($html->find('table.vv tbody ') as $element) {
            foreach($element->find('div[class=rua-g-clearfix] a.t')as $link) {
                $partLinksToJob[] =  $link->href;
            }
        }

        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $linksPart) {
                $fullLinksToJobs['linksToJob'][] = 'http://rabota.ua/' . $linksPart;
            }
        }
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
                $allLinksToJob = array_merge((array)$allLinksToJob, $linksToJob['linksToJob']);
            if ($linksToJob['endOfCycle'] === false)
                break;
        }
        return $allLinksToJob;

    }
}

<?php

include_once '../abstractClass/MainVacationPageParser.php';
include_once 'CurlInit_stackoverflow.php';

class MainVacationPageParser_stackoverflow extends MainVacationPageParser
{
    private function linksParse($url, $tag)
    {
        $curlInit = new CurlInit_stackoverflow();
        $curlResult = $curlInit->getCurlInit($url);
        $html = new simple_html_dom();
        $html->load($curlResult);
        $fullLinksToJobs = array('linksToJob' => array(), 'endOfCycle' => 'true');
        foreach ($html->find('div[class=listResults -jobs list jobs]') as $element) {
            foreach ($element->find('div.listResults div.tags') as $tagsName) {
                if (strpos(strtolower($tagsName), strtolower($tag)) !== false) {
                    $partLinksToJob[] = $tagsName->parentNode()->childNodes(2)->childNodes(0)->href;
                } else {
                    $fullLinksToJobs['endOfCycle'] = false;
                    break 2;
                }
            }
        }
        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $linksPart) {
                $fullLinksToJobs['linksToJob'][] = 'http://careers.stackoverflow.com/' . $linksPart;
            }
        }
        return $fullLinksToJobs;
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
        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            $linksToJob = $this->linksParse($urlWithPageNumber, $searchTag);
            if ($linksToJob != null && is_array($linksToJob))
                $allLinksToJob = array_merge((array)$allLinksToJob, $linksToJob['linksToJob']);
            if ($linksToJob['endOfCycle'] === false)
                break;
        }

        return $allLinksToJob;

    }
}
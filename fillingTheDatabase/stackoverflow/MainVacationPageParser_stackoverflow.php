<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

include_once DOCUMENT_ROOT.'/Search/abstractClass/MainVacationPageParser.php';
include_once  DOCUMENT_ROOT.'/Search/stackoverflow/CurlInit_stackoverflow.php';
include_once DOCUMENT_ROOT.'/Search/lib/simpl/simple_html_dom.php';


class MainVacationPageParser_stackoverflow extends MainVacationPageParser
{
    private function linksParse($url, $tag)
    {
        $curlInit = new CurlInit_stackoverflow();
        $curlResult = $curlInit->getCurlInit($url);

        $html = new simple_html_dom();
        $html->load($curlResult);

        $linksToJobDateAddAndTags = array();

        
        foreach ($html->find('div[class=listResults -jobs list jobs]') as $element) {

            foreach ($element->find('div.listResults div.tags') as $key=>$tagsName) {
                    $partLinksToJob[] = $tagsName->parentNode()->childNodes(2)->childNodes(0)->href;
                    $dateAdd[] = $tagsName->parentNode()->childNodes(0)->innertext;
                
                foreach ($tagsName->find('a') as $val) {
                    if ($key != $check)
                        $tags[$key] = array();

                    $tags[$key][] = $val->innertext;
                    $check = $key;
                }
            }
        }

        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $key => $linksPart) {

                $linksToJobDateAddAndTags[] = array('linkToJob' => 'http://careers.stackoverflow.com/' . $linksPart,
                    'dateAdd' => $dateAdd[$key],
                    'tags'=>$tags[$key]);
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

        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            $linksToJob = $this->linksParse($urlWithPageNumber, $searchTag);
            if ($linksToJob != null && is_array($linksToJob))
                $allLinksToJobDateAddAndTags = array_merge((array)$allLinksToJobDateAddAndTags, $linksToJob);
        }

        return $allLinksToJobDateAddAndTags;

    }
}
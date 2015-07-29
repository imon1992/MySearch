<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/dou/CurlInit_Dou.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/abstractClass/MainVacationPageParser.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/general/GenerateUrl.php';

class MainVacationPageParser_dou extends MainVacationPageParser
{
    private function parseFirstPart($url)
    {
        $curlInit = new CurlInit_Dou();
        $curlResult = $curlInit->getCurlInit($url);
        preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $curlResult, $linksToJobs);
        return $linksToJobs;
    }

    protected function generateAllLinks($searchTag)
    {
        $searchTag = parent::changSumSymbols($searchTag);

        $generateUrl = new GenerateUrl();

        $url = $generateUrl->generateUrlFirstPageDou($searchTag);

        $html = file_get_html($url);

        foreach ($html->find('div.b-vacancies-head h1') as $element) {
            $arrayReferencesVacancies[] = $element->innertext;
        }

        preg_match_all("/\d+/", $arrayReferencesVacancies[0], $numberOfVacancies);
        $numberOfVacancies = $numberOfVacancies[0][0];

        if ($numberOfVacancies < 20) {
            $numberOfIterations = 0;
        } else {
            $numberOfIterations = ceil(($numberOfVacancies - 20) / 40);
        }

        $firstPartJobs = $this->parseFirstPart($url);

        foreach ($firstPartJobs[0] as $element) {
            $firstArray[] = $element;
        }

        $url = $generateUrl->generateUrlNextPartDou($searchTag);

        $curlInit = new CurlInit_Dou();

        for ($nextNumberOfVacation = 20; $nextNumberOfVacation <= ($numberOfIterations * 40) + 20; $nextNumberOfVacation += 40) {
            $curlResult = $curlInit->getCurlInit($url, $nextNumberOfVacation);
            preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $curlResult, $secondPartJobs);
            foreach ($secondPartJobs[0] as $element) {
                $secondArray[] = $element;
            }
        }

        if ($numberOfVacancies > 20) {
            $linksToJobs = array_merge($firstArray, $secondArray);
        } else {
            $linksToJobs = $firstArray;
        }

        array_push($linksToJobs, $searchTag);

        return $linksToJobs;
    }
}

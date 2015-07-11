<?php
header("Content-Type: text/html; charset=utf-8");
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/dou/CurlInit_Dou.php';


include_once DOCUMENT_ROOT.'/Search/abstractClass/MainVacationPageParser.php';
include_once DOCUMENT_ROOT.'/Search/general/GenerateUrl.php';

class MainVacationPageParser_dou extends MainVacationPageParser
{
    private function parseFirstPart($url)
    {
        $curlInit = new CurlInit_Dou();
        $curlResult = $curlInit->getCurlInit($url);
        preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $curlResult, $linksToJobs);
//        var_dump($linksToJobs);
        return $linksToJobs;
    }

    protected function generateAllLinks($searchTag)
    {
        $searchTag = parent::changSumSymbols($searchTag);
//        var_dump($searchTag);
$generateUrl = new GenerateUrl();
        $url = $generateUrl->generateUrlFirstPageDou($searchTag);
//        var_dump($url);
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
//        var_dump($firstArray);
//        var_dump($secondArray);
        if ($numberOfVacancies > 20) {
            $linksToJobs = array_merge($firstArray, $secondArray);
        } else {
            $linksToJobs = $firstArray;
        }
        array_push($linksToJobs,$searchTag);
//        var_dump($linksToJobs);
        return $linksToJobs;
    }
}

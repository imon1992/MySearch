<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_rabota extends ParseDataFromLinks
{
     protected function processingReferences($linksToJobsArray)
    {
//        var_dump($linksToJobsArray);
        $searchTag = array_pop($linksToJobsArray);
//        var_dump($searchTag);
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/vacancy\d+/", $linksToJobsArray[$i]['linkToJob'], $arrayOfVacancyId);
//                preg_match("/\/company\d+\//", $linksToJobsArray[$i]['linkToJob'], $arrayOfCompanyId);
                preg_match("/\d+/", $arrayOfVacancyId[0], $vacancyId);
//                preg_match("/\d+/", $arrayOfCompanyId[0], $companyId);
                $idOfVacancies = $vacancyId[0];
//                $idOfCompany = $companyId[0];
                $idVacanciesLinksCIdCompanyDateAddArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsArray[$i]['linkToJob'],
                    'city'=> $linksToJobsArray[$i]['city'],
                    'dateAdd' => $linksToJobsArray[$i]['dateAdd'],
                    'searchTag' => $searchTag
                );
            }
        }
//        var_dump($idVacanciesLinksCIdCompanyDateAddArray);
        return $idVacanciesLinksCIdCompanyDateAddArray;
    }
}

//$c = new ParseDataFromLinks_rabota();
//$x =$c->getProcessingReferences([0=>['linkToJob'=>'http://rabota.ua//company2105052/vacancy5735155','dateAdd'=>'16 часов назад']]);
//echo "<pre>";
//print_r($x);
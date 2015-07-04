<?php
include_once '../abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_rabota extends ParseDataFromLinks
{
     function processingReferences($linksToJobsArray)
    {
        var_dump($linksToJobsArray[0]);
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/vacancy\d+/", $linksToJobsArray[$i]['linkToJob'], $arrayOfVacancyId);
                preg_match("/\/company\d+\//", $linksToJobsArray[$i]['linkToJob'], $arrayOfCompanyId);
                preg_match("/\d+/", $arrayOfVacancyId[0], $vacancyId);
                preg_match("/\d+/", $arrayOfCompanyId[0], $companyId);
                $idOfVacancies = $vacancyId[0];
                $idOfCompany = $companyId[0];
                $idAndLinksArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsArray[$i]['linkToJob'],
                    'companyId' => $idOfCompany,
                    'dateAdd' => $linksToJobsArray[$i]['dateAdd']
                );
            }
        }
        return $idAndLinksArray;
    }
}
//$links = [0=>['linkToJob'=>'http://rabota.ua//company1556693/vacancy5679349','dateAdd'=>'2недели']];
//
//$c = new ParseDataFromLinks_rabota();
//$x = $c->getProcessingReferences($links);
//echo '<pre>';
//print_r($x);
<?php
include_once '../abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_rabota extends ParseDataFromLinks
{
     function processingReferences($linksToJobsArray)
    {
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/vacancy\d+/", $linksToJobsArray[$i], $arrayOfVacancyId);
                preg_match("/\/company\d+\//", $linksToJobsArray[$i], $arrayOfCompanyId);
                preg_match("/\d+/", $arrayOfVacancyId[0], $vacancyId);
                preg_match("/\d+/", $arrayOfCompanyId[0], $companyId);
                $idOfVacancies = $vacancyId[0];
                $idOfCompany = $companyId[0];
                $idAndLinksArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsArray[$i],
                    'companyId' => $idOfCompany
                );
            }
        }
        return $idAndLinksArray;
    }
}
//$links = ['http://rabota.ua//company1556693/vacancy5679349',
//    'http://rabota.ua//company1556693/vacancy5751139',
//    'http://rabota.ua//company880/vacancy5768639'];
//
//$c = new ParseDataFromLinks_rabota();
//$x = $c->getProcessingReferences($links);
//echo '<pre>';
//print_r($x);
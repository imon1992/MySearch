<?php
include_once '../abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_rabota extends ParseDataFromLinks
{
     protected function processingReferences($linksToJobsArray)
    {
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/vacancy\d+/", $linksToJobsArray[$i]['linkToJob'], $arrayOfVacancyId);
                preg_match("/\/company\d+\//", $linksToJobsArray[$i]['linkToJob'], $arrayOfCompanyId);
                preg_match("/\d+/", $arrayOfVacancyId[0], $vacancyId);
                preg_match("/\d+/", $arrayOfCompanyId[0], $companyId);
                $idOfVacancies = $vacancyId[0];
                $idOfCompany = $companyId[0];
                $idVacanciesLinksCIdCompanyDateAddArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsArray[$i]['linkToJob'],
                    'companyId' => $idOfCompany,
                    'dateAdd' => $linksToJobsArray[$i]['dateAdd']
                );
            }
        }
        return $idVacanciesLinksCIdCompanyDateAddArray;
    }
}

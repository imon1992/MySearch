<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_dou extends ParseDataFromLinks
{
    protected function processingReferences($linksToJobsArray)
    {
        $searchTag = array_pop($linksToJobsArray);
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);

            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/vacancies\/\d+/", $linksToJobsArray[$i], $arrayOfVacancies);
                preg_match("/\d+/", $arrayOfVacancies[0], $arrayOfVacanciesId);
                $idOfVacancies = $arrayOfVacanciesId[0];
                $beginningCompaniesPosition = stripos($linksToJobsArray[$i], 'companies/');
                $lengthURL = strlen($linksToJobsArray[$i]);
                $newLine = substr($linksToJobsArray[$i], $beginningCompaniesPosition + 10, $lengthURL);
                $searchPosition = stripos($newLine, '/vacancies');
                $lengthNewLine = strlen($newLine);
                $companyName = substr($newLine, -$lengthNewLine, $searchPosition);
                $vacancyIdTagAndCompany[] = array(
                    'company' => "$companyName",
                    'id_vacancies' => "$idOfVacancies",
                    'searchTag' => $searchTag
                );
            }
        }
        return $vacancyIdTagAndCompany;
    }
}
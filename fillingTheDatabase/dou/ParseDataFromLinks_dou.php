<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_dou extends ParseDataFromLinks
{
    protected function processingReferences($linksToJobsArray)
    {
//        var_dump($linksToJobsArray);
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
                $data[] = array(
                    'id_vacancies' => "$idOfVacancies",
                    'searchTag' => $searchTag
                );
            }
        }
//        var_dump($data);
        return $data;
    }
}
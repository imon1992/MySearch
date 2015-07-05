<?php
include_once '../abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_stackoverflow extends ParseDataFromLinks
{
    protected function processingReferences($linksToJobsDateAddArray)
    {
        if (!empty($linksToJobsDateAddArray)) {
            $linksToJobsLength = sizeof($linksToJobsDateAddArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/\d+\//", $linksToJobsDateAddArray[$i]['linkToJob'], $arrayOfVacancies);
                preg_match("/\d+/", $arrayOfVacancies[0], $arrayOfVacanciesId);
                $idOfVacancies = $arrayOfVacanciesId[0];
                $idVacancyLinksDateAddArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsDateAddArray[$i]['linkToJob'],
                    'dateAdd' => $linksToJobsDateAddArray[$i]['dateAdd']
                );
            }
        }
        return $idVacancyLinksDateAddArray;
    }
}
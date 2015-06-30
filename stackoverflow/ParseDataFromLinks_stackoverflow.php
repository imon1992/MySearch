<?php
include_once '../abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_stackoverflow extends ParseDataFromLinks
{
    protected function processingReferences($linksToJobsArray)
    {
        if (!empty($linksToJobsArray)) {
            $linksToJobsLength = sizeof($linksToJobsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/\d+\//", $linksToJobsArray[$i], $arrayOfVacancies);
                preg_match("/\d+/", $arrayOfVacancies[0], $arrayOfVacanciesId);
                $idOfVacancies = $arrayOfVacanciesId[0];
                $idAndLinksArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsArray[$i]
                );
            }
        }
        return $idAndLinksArray;
    }
}

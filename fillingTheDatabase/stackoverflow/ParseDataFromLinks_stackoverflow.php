<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_stackoverflow extends ParseDataFromLinks
{
    protected function processingReferences($linksToJobsDateAddArray)
    {
//        var_dump($linksToJobsDateAddArray);
        $searchTag = array_pop($linksToJobsDateAddArray);
        if (!empty($linksToJobsDateAddArray)) {
            $linksToJobsLength = sizeof($linksToJobsDateAddArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/\d+\//", $linksToJobsDateAddArray[$i]['linkToJob'], $arrayOfVacancies);
                preg_match("/\d+/", $arrayOfVacancies[0], $arrayOfVacanciesId);
                $idOfVacancies = $arrayOfVacanciesId[0];
                $idVacancyLinksDateAddArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $linksToJobsDateAddArray[$i]['linkToJob'],
                    'dateAdd' => $linksToJobsDateAddArray[$i]['dateAdd'],
                    'searchTag' => $searchTag
//                    'city' => $linksToJobsDateAddArray[$i]['city']
                );
            }
        }
//        var_dump($idVacancyLinksDateAddArray);
        return $idVacancyLinksDateAddArray;
    }
}
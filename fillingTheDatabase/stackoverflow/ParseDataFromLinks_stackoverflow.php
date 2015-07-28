<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_stackoverflow extends ParseDataFromLinks
{
    protected function processingReferences($allLinksToJobDateAddAndTagsArray)
    {
        if (!empty($allLinksToJobDateAddAndTagsArray)) {

            $searchTag = array_pop($allLinksToJobDateAddAndTagsArray);
            $linksToJobsLength = sizeof($allLinksToJobDateAddAndTagsArray);
            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/\d+\//", $allLinksToJobDateAddAndTagsArray[$i]['linkToJob'], $arrayOfVacancies);
                preg_match("/\d+/", $arrayOfVacancies[0], $arrayOfVacanciesId);
                $idOfVacancies = $arrayOfVacanciesId[0];
                $idVacancyLinksDateAddAndTags[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $allLinksToJobDateAddAndTagsArray[$i]['linkToJob'],
                    'dateAdd' => $allLinksToJobDateAddAndTagsArray[$i]['dateAdd'],
                    'tags' => $allLinksToJobDateAddAndTagsArray[$i]['tags'],
                    'searchTag' => $searchTag

                );
            }
        }
        return $idVacancyLinksDateAddAndTags;
    }
}
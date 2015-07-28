<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/abstractClass/ParseDataFromLinks.php';

class ParseDataFromLinks_rabota extends ParseDataFromLinks
{
    protected function processingReferences($allLinksToJobDateAddCityAndTags)
    {
        $searchTag = array_pop($allLinksToJobDateAddCityAndTags);
        if (!empty($allLinksToJobDateAddCityAndTags)) {
            $linksToJobsLength = sizeof($allLinksToJobDateAddCityAndTags);

            for ($i = 0; $i < $linksToJobsLength; $i++) {
                preg_match("/\/vacancy\d+/", $allLinksToJobDateAddCityAndTags[$i]['linkToJob'], $arrayOfVacancyId);
                preg_match("/\d+/", $arrayOfVacancyId[0], $vacancyId);
                $idOfVacancies = $vacancyId[0];
                $idVacanciesLinksToJobCityDateAddAndTagsArray[] = array(
                    'id_vacancies' => $idOfVacancies,
                    'linksToJob' => $allLinksToJobDateAddCityAndTags[$i]['linkToJob'],
                    'city' => $allLinksToJobDateAddCityAndTags[$i]['city'],
                    'dateAdd' => $allLinksToJobDateAddCityAndTags[$i]['dateAdd'],
                    'tags' => $allLinksToJobDateAddCityAndTags[$i]['tags'],
                    'searchTag' => $searchTag
                );
            }
        }
        return $idVacanciesLinksToJobCityDateAddAndTagsArray;
    }
}

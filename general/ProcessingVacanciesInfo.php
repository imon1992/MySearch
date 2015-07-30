<?php

include_once '../BD/WorkWithDb.php';
include_once '../general/ProcessingWithTableNameAndFields.php';

class ProcessingVacanciesInfo
{
    public function getVacanciesInfo($dateInfo, $className, $searchTagCityAndDate)
    {$city = null;
        if (property_exists($searchTagCityAndDate,'city')) {
            $city = $searchTagCityAndDate->city;
        }
        $searchTag = $searchTagCityAndDate->searchTag;
        $from = $dateInfo['from'];
        $by = $dateInfo['by'];

        $processingWithTableNameAndFields = new ProcessingWithTableNameAndField();

        $tableNameVacancyInfo = $processingWithTableNameAndFields->generateVacancyInfoTableName($className);
        $tableNameCities = $processingWithTableNameAndFields->generateCitiesTableName($className);
        $tableNameTags = $processingWithTableNameAndFields->generateTagsTableName($className);

        $tableFieldsVacancyInfo = $processingWithTableNameAndFields->generateTableFieldsIdVacancyAndText($tableNameVacancyInfo);
        $tableFieldIdVacancyVacancyInfo = $tableFieldsVacancyInfo['fieldIdVacancy'];
        $tableFieldTextVacancyVacancyInfo = $tableFieldsVacancyInfo['fieldTextVacancy'];
        $tableFieldDateAddVacancyInfo = $processingWithTableNameAndFields->generateTableFieldIDateAddVacancyInfo($tableNameVacancyInfo);

        $tableFieldIdVacancyCities = $processingWithTableNameAndFields->generateTableFieldIdVacancy($tableNameCities);
        $tableFieldCityCities = $processingWithTableNameAndFields->generateTableFieldCityCities($tableNameCities);

        $tableFieldIdVacancyTags = $processingWithTableNameAndFields->generateTableFieldIdVacancy($tableNameTags);
        $tableFieldTagTags = $processingWithTableNameAndFields->generateTableFieldTagTags($tableNameTags);

        $db = WorkWithDb::getInstance();

        if ($city != null) {
            $wordCount = str_word_count($city);
            if ($wordCount == 1) {
                $vacanciesInfo = $db->getVacancyInfoByDateWithCity($from, $by, $tableNameVacancyInfo, $city, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo, $tableFieldTextVacancyVacancyInfo, $searchTag,
                    $tableNameCities, $tableFieldIdVacancyCities, $tableFieldCityCities, $tableNameTags, $tableFieldIdVacancyTags,
                    $tableFieldTagTags);
            } else {
                $vacanciesInfo = $db->getVacancyInfoByDateWithCityLike($from, $by, $tableNameVacancyInfo, $city, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo, $tableFieldTextVacancyVacancyInfo, $searchTag,
                    $tableNameCities, $tableFieldIdVacancyCities, $tableFieldCityCities, $tableNameTags, $tableFieldIdVacancyTags,
                    $tableFieldTagTags);
            }
        } else {
            $vacanciesInfo = $db->getVacancyInfoByDate($from, $by, $tableNameVacancyInfo, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo,
                $tableFieldTextVacancyVacancyInfo, $searchTag, $tableNameTags, $tableFieldIdVacancyTags,
                $tableFieldTagTags);
        }
        $vacanciesMap = $this->createMap($vacanciesInfo);
        return $vacanciesMap;
    }

    public function createMap($dbAnswer)
    {
        $vacancyMap = array();
        foreach ($dbAnswer as $vacancyInfo) {
            $vacancyMap[$vacancyInfo['id_vacancies']] = ['text' => $vacancyInfo['text_vacancies']];
        }
        return $vacancyMap;
    }
}

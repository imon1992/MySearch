<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/search/abstractClass/CacheGetter.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Search/BD/WorkWithDB.php';

class CacheGetter_rabota extends CacheGetter
{
    protected function formationMapWithText($idVacanciesLinksToJobCityDateAddAndTagsArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idVacanciesLinksToJobCityDateAddAndTagsArray);

        $db = WorkWithDB::getInstance();
        $processingWithTableNameAndField = new ProcessingWithTableNameAndField();
        $tableNameVacancyInfo = $processingWithTableNameAndField->generateVacancyInfoTableName(__CLASS__);
        $tableFieldsVacancyIdAndText = $processingWithTableNameAndField->generateTableFieldsIdVacancyAndText($tableNameVacancyInfo);
        $idVacancyField = $tableFieldsVacancyIdAndText['fieldIdVacancy'];
        $textVacancyField = $tableFieldsVacancyIdAndText['fieldTextVacancy'];

        foreach ($idVacanciesLinksToJobCityDateAddAndTagsArray as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
                'city' => $vacancyInfo['city'],
                'dateAdd' => $vacancyInfo['dateAdd'],
                'tags' => $vacancyInfo['tags'],
                'searchTag' => $vacancyInfo['searchTag'],
                'text' => null);
        }

        $dbAnswer = $db->getVacancyIdAndText($arrayOfId, $tableNameVacancyInfo, $idVacancyField, $textVacancyField);
        $dbAnswerMap = array();
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }
        $vacancyIdAndTextMap = array();
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $this->checkKey($dbAnswerMap, $vacancyId)) {
                continue;
            } else {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'city' => $vacancyMap[$vacancyId]['city'],
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'tags' => $vacancyMap[$vacancyId]['tags'],
                    'searchTag' => $vacancyMap[$vacancyId]['searchTag'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }

        return $vacancyIdAndTextMap;
    }

    function checkKey($array, $key)
    {
        return array_key_exists($key, $array) ? $array[$key] : null;
    }
}
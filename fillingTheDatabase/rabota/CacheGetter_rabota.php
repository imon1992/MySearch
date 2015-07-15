<?php
header("Content-Type: text/html; charset=utf-8");
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once DOCUMENT_ROOT . '/search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT . '/Search/BD/WorkWithDB.php';

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

        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }

        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $dbAnswerMap[$vacancyId]) {
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
}
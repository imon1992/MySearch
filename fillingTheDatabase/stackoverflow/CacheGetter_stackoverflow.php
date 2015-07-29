<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/abstractClass/CacheGetter.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/BD/WOrkWithDB.php';

class CacheGetter_stackoverflow extends CacheGetter
{
    protected function formationMapWithText($idVacancyLinksDateAddAndTags)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idVacancyLinksDateAddAndTags);

        $db = WorkWithDB::getInstance();

        $processingWithTableNameAndField = new ProcessingWithTableNameAndField();
        $tableNameVacancyInfo = $processingWithTableNameAndField->generateVacancyInfoTableName(__CLASS__);
        $tableFieldsVacancyIdAndText = $processingWithTableNameAndField->generateTableFieldsIdVacancyAndText($tableNameVacancyInfo);
        $idVacancyField = $tableFieldsVacancyIdAndText['fieldIdVacancy'];
        $textVacancyField = $tableFieldsVacancyIdAndText['fieldTextVacancy'];

        foreach ($idVacancyLinksDateAddAndTags as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
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
        $vacancyIdLinksDateAddTextMap = array();
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $this->checkKey($dbAnswerMap, $vacancyId)) {
                continue;
            } else {
                $vacancyIdLinksDateAddTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'tags' => $vacancyMap[$vacancyId]['tags'],
                    'searchTag' => $vacancyMap[$vacancyId]['searchTag'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdLinksDateAddTextMap;
    }
}

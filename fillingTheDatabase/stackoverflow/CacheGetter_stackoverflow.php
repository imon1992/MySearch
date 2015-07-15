<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

require_once DOCUMENT_ROOT . '/Search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT . '/Search/BD/WOrkWithDB.php';

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
                $vacancyIdLinksDateAddTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'tags' => $vacancyMap[$vacancyId]['tags'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdLinksDateAddTextMap;
    }
}

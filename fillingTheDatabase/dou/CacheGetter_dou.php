<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

require_once DOCUMENT_ROOT . '/Search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT . '/Search/BD/WorkWithDB.php';

class CacheGetter_dou extends CacheGetter
{
    protected function formationMapWithText($vacancyIdTagAndCompanyArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($vacancyIdTagAndCompanyArray);

        $db = WorkWithDB::getInstance();
        $processingWithTableNameAndField = new ProcessingWithTableNameAndField();

        $tableNameVacancyInfo = $processingWithTableNameAndField->generateVacancyInfoTableName(__CLASS__);
        $tableFieldsVacancyIdAndText = $processingWithTableNameAndField->generateTableFieldsIdVacancyAndText($tableNameVacancyInfo);

        $idVacancyField = $tableFieldsVacancyIdAndText['fieldIdVacancy'];
        $textVacancyField = $tableFieldsVacancyIdAndText['fieldTextVacancy'];

        foreach ($vacancyIdTagAndCompanyArray as $key => $idAndCompany) {
            $vacancyMap[$idAndCompany['id_vacancies']] = array('id_vacancies' => $idAndCompany['id_vacancies'],
                'company' => $vacancyIdTagAndCompanyArray [$key]['company'],
                'searchTag' => $vacancyIdTagAndCompanyArray [$key]['searchTag'],
                'text' => null);
        }

        $dbAnswer = $db->getVacancyIdAndText($arrayOfId, $tableNameVacancyInfo, $idVacancyField, $textVacancyField);

        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }

        foreach ($vacancyMap as $vacancyId => $vacancyCompanyAdnTag) {
            if (null != $dbAnswerMap[$vacancyId]) {
                continue;
            } else {
                $vacancyIdCompanyTagAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'searchTag' => $vacancyCompanyAdnTag['searchTag'],
                    'company' => $vacancyCompanyAdnTag['company'],
                    'text' => null);
            }
        }
        return $vacancyIdCompanyTagAndTextMap;
    }
}
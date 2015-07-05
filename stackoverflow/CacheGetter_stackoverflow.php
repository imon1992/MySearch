<?php
require_once '../abstractClass/CacheGetter.php';
include_once '../BD/WorkWithDb.stackoverflow.class.php';
class CacheGetter_stackoverflow extends CacheGetter
{
    protected function formationMapWithText($idVacanciesLinksDateAddArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idVacanciesLinksDateAddArray);
        foreach ($idVacanciesLinksDateAddArray as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
                'dateAdd' => $vacancyInfo['dateAdd'],
                'text' => null);
        }

        $db = WorkWithDB1::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $dbAnswerMap[$vacancyId]) {
                $vacancyIdLinksDateAddTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'text' => $dbAnswerMap[$vacancyId]['text']);
            } else {
                $vacancyIdLinksDateAddTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdLinksDateAddTextMap;
    }
}

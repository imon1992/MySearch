<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

require_once DOCUMENT_ROOT.'/Search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT.'/Search/BD/WOrkWithDB.stackoverflow.class.php';
class CacheGetter_stackoverflow extends CacheGetter
{
    protected function formationMapWithText($idVacanciesLinksDateAddArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idVacanciesLinksDateAddArray);
        foreach ($idVacanciesLinksDateAddArray as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
                'dateAdd' => $vacancyInfo['dateAdd'],
                'searchTag' => $vacancyInfo['searchTag'],
//                'city' => $vacancyInfo['city'],
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
continue;
            } else {
                $vacancyIdLinksDateAddTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'searchTag' => $vacancyMap[$vacancyId]['searchTag'],
//                    'city' => $vacancyMap[$vacancyId]['city'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
//        var_dump($vacancyIdLinksDateAddTextMap);
        return $vacancyIdLinksDateAddTextMap;
    }
}

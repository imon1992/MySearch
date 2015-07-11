<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

require_once DOCUMENT_ROOT.'/Search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT.'/Search/BD/WorkWithDB.DOU.class.php';

class CacheGetter_dou extends CacheGetter
{
    protected function formationMapWithText($idAndCompanyArray)
    {
//        var_dump($idAndCompanyArray);
//        $searchTag = array_pop($idAndCompanyArray);
        $arrayOfId = parent::getAllIdOfVacancies($idAndCompanyArray);
        foreach ($idAndCompanyArray as $key => $idAndCompany) {
            $vacancyMap[$idAndCompany['id_vacancies']] = array('id_vacancies' => $idAndCompany['id_vacancies'],
                'searchTag' =>$idAndCompanyArray [$key]['searchTag'],
                'text' => null);
        }
        $db = WorkWithDB::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'searchTag' =>$idAndCompanyArray [$key]['searchTag'],
                'text' => $textAndId['text_vacancies']);
        }
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $dbAnswerMap[$vacancyId]) {
continue;
            } else {
                $vacancyIdAndCompanyAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'searchTag' => $vacancyIdAndCompany['searchTag'],
                    'text' => null);
            }
        }
//        var_dump()
        return $vacancyIdAndCompanyAndTextMap;
    }
}
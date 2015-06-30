<?php
require_once '../abstractClass/CacheGetter.php';

class CacheGetter_dou extends CacheGetter
{
    protected function formationMapWithText($idAndCompanyArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idAndCompanyArray);
        foreach ($idAndCompanyArray as $key => $idAndCompany) {
            $vacancyMap[$idAndCompany['id_vacancies']] = array('id_vacancies' => $idAndCompany['id_vacancies'],
                'company' => $idAndCompanyArray [$key]['company'],
                'text' => null);
        }
        $db = WorkWithDB::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'company' => $idAndCompanyArray [$key]['company'],
                'text' => $textAndId['text_vacancies']);
        }
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $dbAnswerMap[$vacancyId]) {
                $vacancyIdAndCompanyAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'company' => $vacancyIdAndCompany['company'],
                    'text' => $dbAnswerMap[$vacancyId]['text']);
            } else {
                $vacancyIdAndCompanyAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'company' => $vacancyIdAndCompany['company'],
                    'text' => null);
            }
        }
        return $vacancyIdAndCompanyAndTextMap;
    }
}
<?php
header("Content-Type: text/html; charset=utf-8");
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once DOCUMENT_ROOT.'/search/abstractClass/CacheGetter.php';
include_once DOCUMENT_ROOT.'/Search/BD/WorkWithDB.rabota.php';

class CacheGetter_rabota extends CacheGetter
{
    protected function formationMapWithText($idAndLinksArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idAndLinksArray);
        foreach ($idAndLinksArray as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
                'city' => $vacancyInfo['city'],
                'dateAdd' => $vacancyInfo['dateAdd'],
                'searchTag' => $vacancyInfo['searchTag'],
                'text' => null);
        }

        $db = WorkWithDB2::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
            if (null != $dbAnswerMap[$vacancyId]) {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'companyId' => $vacancyMap[$vacancyId]['companyId'],
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'searchTag' => $vacancyMap[$vacancyId]['searchTag'],
                    'text' => $dbAnswerMap[$vacancyId]['text']);
            } else {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'city' => $vacancyMap[$vacancyId]['city'],
                    'dateAdd' => $vacancyMap[$vacancyId]['dateAdd'],
                    'searchTag' => $vacancyMap[$vacancyId]['searchTag'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdAndTextMap;
    }
}

//$c = new CacheGetter_rabota();
//$x = $c->getMapWithText([0=>['id_vacancies' => 5735155,
//            'linksToJob' => 'http://rabota.ua//company2105052/vacancy5735155',
//            'companyId' => 2105052,
//            'dateAdd' => '16 С‡Р°СЃРѕРІ РЅР°Р·Р°Рґ']]);
////echo '<pre>';
////print_r($x);
//include_once 'ProcessingDataArrayWithText_rabota.php';
//$r = new ProcessingDataArrayWithText_rabota();
//$xx = $r->getTheMissingText($x);
//echo '<pre>';
//print_r($xx);
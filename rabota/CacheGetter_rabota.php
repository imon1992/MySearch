<?php
require_once '../abstractClass/CacheGetter.php';
include_once '../BD/WorkWithDb.rabota.php';
class CacheGetter_rabota extends CacheGetter
{
    protected function formationMapWithText($idAndLinksArray)
    {
        $arrayOfId = parent::getAllIdOfVacancies($idAndLinksArray);
        foreach ($idAndLinksArray as $vacancyInfo) {

            $vacancyMap[$vacancyInfo['id_vacancies']] = array('id_vacancies' => $vacancyInfo['id_vacancies'],
                'linksToJob' => $vacancyInfo['linksToJob'],
                'companyId' => $vacancyInfo['companyId'],
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
                    'text' => $dbAnswerMap[$vacancyId]['text']);
            } else {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'companyId' => $vacancyMap[$vacancyId]['companyId'],
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdAndTextMap;
    }
}

//$c = new CacheGetter_rabota();
//$x = $c->getMapWithText(array('0'=>['id_vacancies'=>'5679349','linksToJob'=>'http://rabota.ua//company1556693/vacancy5751139','companyId'=>'1556693']));
//echo '<pre>';
////print_r($x);
//include_once 'ProcessingDataArrayWithText_rabota.php';
//$r = new ProcessingDataArrayWithText_rabota();
//$t = $r->takeTheMissingText($x);
//print_r($t);
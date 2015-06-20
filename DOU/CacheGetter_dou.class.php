<?php

//проверяет есть ли в базе такая запись, если есть достает ей и ложит в поле text, если нету то в поле текст ложиться null
include_once '../BD/WorkWithDB.DOU.class.php';

class CacheGetter_dou {
    function formationMapWithText($idAndCompanyArray) {
//        var_dump($idAndCompanyArray);z
        foreach ($idAndCompanyArray as $val) {
            $arrayOfId[] = $val['id_vacancies'];
        }
//        var_dump($arrayOfId);
        foreach ($idAndCompanyArray as $key => $idAndCompany) {
            $vacancyMap[$idAndCompany[id_vacancies]] = array('id_vacancies' => $idAndCompany['id_vacancies'],
                'company' => $idAndCompanyArray [$key]['company'],
                'text' => null);
        }
//        var_dump($arrayOfId);
        $db = WorkWithDB::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
//        var_dump($dbAnswer);
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
//var_dump($vacancyIdAndCompanyAndTextMap);
        return $vacancyIdAndCompanyAndTextMap;
    }

}
//$c = new CacheGetter();
//$x = $c->formationMapWithText(array(array('company'=>'templatemonster','id_vacancies'=>17657)));
//echo'<pre>';
//print_r($x);

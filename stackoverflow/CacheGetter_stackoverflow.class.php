<?php
//namespace stackoverflow;
//use BD\WorkWithDB;
//проверяет есть ли в базе такая запись, если есть достает ей и ложит в поле text, если нету то в поле текст ложиться null
include_once '../BD/WorkWithDB.stackoverflow.class.php';

class CacheGetter_stackoverflow {
    function formationMapWithText($idAndLinksArray) {
//        var_dump($idAndLinksArray);
        foreach ($idAndLinksArray as $val) {
            $arrayOfId[] = $val['id_vacancies'];
        }
        foreach ($idAndLinksArray as $id) {
//            echo $id;
            $vacancyMap[$id['id_vacancies']] = array('id_vacancies' => $id['id_vacancies'],
                'linksToJob' => $id['linksToJob'],
                'text' => null);
        }
//        echo'<pre>';
//        print_r($vacancyMap);
        $db = WorkWithDB1::getInstance();
        $dbAnswer = $db->giveData($arrayOfId);
//        var_dump($dbAnswer);
        foreach ($dbAnswer as $key => $textAndId) {
            $dbAnswerMap[$textAndId['id_vacancies']] = array('id_vacancies' => $textAndId['id_vacancies'],
                'text' => $textAndId['text_vacancies']);
        }
        foreach ($vacancyMap as $vacancyId => $vacancyIdAndCompany) {
//            var_dump($vacancyMap[$vacancyId]['linksToJob']);
            if (null != $dbAnswerMap[$vacancyId]) {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => $dbAnswerMap[$vacancyId]['text']);
//                    unset($vacancyIdAndTextMap[$vacancyId]['linksToJob']);
            } else {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
//var_dump($vacancyIdAndCompanyAndTextMap);
        return $vacancyIdAndTextMap;
    }

}
//$c = new CacheGetter();
//$x = $c->formationMapWithText(array(array('company'=>'templatemonster','id_vacancies'=>17657)));
//echo'<pre>';
//print_r($x);

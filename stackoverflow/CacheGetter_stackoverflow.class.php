<?php

include_once '../BD/WorkWithDB.stackoverflow.class.php';

class CacheGetter_stackoverflow
{
    function formationMapWithText($idAndLinksArray)
    {

        foreach ($idAndLinksArray as $val) {
            $arrayOfId[] = $val['id_vacancies'];
        }
        foreach ($idAndLinksArray as $id) {

            $vacancyMap[$id['id_vacancies']] = array('id_vacancies' => $id['id_vacancies'],
                'linksToJob' => $id['linksToJob'],
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
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => $dbAnswerMap[$vacancyId]['text']);
            } else {
                $vacancyIdAndTextMap[$vacancyId] = array('id_vacancies' => $vacancyId,
                    'text' => null,
                    'linkToJob' => $vacancyMap[$vacancyId]['linksToJob']);
            }
        }
        return $vacancyIdAndTextMap;
    }

}

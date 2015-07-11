<?php

include_once '../BD/WorkWithDb.php';
include_once '../general/ProcessingWithTableNameAndFields.php';
//include_once '../abstractClass/ProcessingVacanciesInfo';
class ProcessingVacanciesInfo_dou {
    public function getVacanciesInfo($dateInfo,$className,$searchTagCityAndDate){
        var_dump($searchTagCityAndDate);
        $city = $searchTagCityAndDate->city;
        var_dump($city);
        $searchTag = $searchTagCityAndDate->searchTag;
        var_dump($searchTag);
        $from = $dateInfo['from'];
        $by = $dateInfo['by'];

        $processingWithTableNameAndFields = new ProcessingWithTableNameAndField();

        $tableName = $processingWithTableNameAndFields->generateVacancyInfoTableName($className);
        $tableFields = $processingWithTableNameAndFields->generateTableFieldsIdVacancyAndText($tableName);
        $tableFieldIdVacancy = $tableFields['fieldIdVacancy'];
        $tableFieldTextVacancy = $tableFields['fieldTextVacancy'];
        $db = WorkWithDb::getInstance();

        if($city != null) {
//            var_dump($city);
            if($city == 'удаленная работа' || $city == 'работа за рубежом') {
                $vacanciesInfo = $db->getVacancyInfoByDateWithCityLike($from, $by, $tableName, $city,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag);
            }else{
                $vacanciesInfo = $db->getVacancyInfoByDateWithCity($from, $by, $tableName, $city,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag);
            }
        }else{
            $vacanciesInfo = $db->getVacancyInfoByDate($from, $by, $tableName,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag);
        }
//var_dump($vacanciesInfo);
        $vacanciesMap = $this->createMap($vacanciesInfo);
        return $vacanciesMap;
    }

     public function createMap($dbAnswer){
        foreach($dbAnswer as $vacancyInfo){
            $vacancyMap[$vacancyInfo['id_vacancies']] = ['text'=>$vacancyInfo['text_vacancies']];
        }
         return $vacancyMap;
    }
}

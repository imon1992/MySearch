<?php
include_once '../BD/WorkWithDb.php';
include_once '../general/ProcessingWithTableNameAndFields.php';
class ProcessingWithCity
{
    function parseCityFromStringRabota($string)
    {
        preg_match('/<\/i>([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix', $string, $cityWithTag);
        preg_match('/>([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix', $cityWithTag[0], $cityWithTagPart);
        preg_match('/([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix', $cityWithTagPart[0], $city);
        $city = trim($city[0]);
        return $city;
    }

    function parseCityFromStringStackoverflow($string)
    {
        $partString = explode(',', $string);
        $city = trim($partString[0]);
        $city = str_replace(array('&#252;', '&#246', '&#228', '&#223'), array('ue', 'oe', 'ae', 'ss'), $city);
        return $city;
    }

    protected function changSumSymbols($symbol){
        $symbol = str_replace(' ','%20',$symbol);
        $symbol = $tag = str_replace('+','%2B',$symbol);
        $symbol = $tag = str_replace('#','%23',$symbol);
        return $symbol;
    }

    public function getCities($tag,$site){
$tag = $this->changSumSymbols($tag);
        $db = WorkWithDb::getInstance();
        $processingWithTableNameAndFields = new ProcessingWithTableNameAndField();
$partClassName = str_replace('?','_',$site);
        $tableNameCities = $processingWithTableNameAndFields->generateCitiesTableName($partClassName);
        $tableNameTags = $processingWithTableNameAndFields->generateTagsTableName($partClassName);

        $tableFieldVacancyIdCities = $processingWithTableNameAndFields->generateTableFieldIdVacancy($tableNameCities);
        $tableFieldCityCities = $processingWithTableNameAndFields->generateTableFieldCityCities($tableNameCities);

        $tableFieldIdVacancyTags = $processingWithTableNameAndFields->generateTableFieldIdVacancy($tableNameTags);
        $cities = $db->getCities($tableNameCities, $tableNameTags, $tableFieldVacancyIdCities, $tableFieldCityCities,
            $tableFieldIdVacancyTags, $tag);
    return $cities;
    }
}
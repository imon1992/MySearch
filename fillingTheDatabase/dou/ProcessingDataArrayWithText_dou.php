<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/abstractClass/ProcessingDataArrayWithText.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/BD/WorkWithDb.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/dou/ProcessingWithDate_dou.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/general/ProcessingWithTableNameAndFields.php';

class ProcessingDataArrayWithText_dou extends ProcessingDataArrayWithText
{
    function takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray)
    {
        if (!empty($idAndCompaniesAndMayNotBeCompleteTextArray)) {

            $db = WorkWithDB::getInstance();
            $generateDateInfo = new GenerateDataParams_dou();

            $processingWithTableNameAndField = new ProcessingWithTableNameAndField();
            $tableNameCities = $processingWithTableNameAndField->generateCitiesTableName(__CLASS__);
            $tableNameVacancyInfo = $processingWithTableNameAndField->generateVacancyInfoTableName(__CLASS__);
            $tableNameTags = $processingWithTableNameAndField->generateTagsTableName(__CLASS__);

            foreach ($idAndCompaniesAndMayNotBeCompleteTextArray as $vacancyId => $idAndCompanyAndTextMap) {
                if ($idAndCompanyAndTextMap['text'] == null) {
                    $tag = $idAndCompanyAndTextMap['searchTag'];

                    $companyName = $idAndCompanyAndTextMap['company'];
                    $http = "http://jobs.dou.ua/companies/$companyName/vacancies/$vacancyId/";

                    usleep(100000);                                                                 //микросекунды;


                    $html = file_get_html($http);

                    if ($html == FALSE) {
                        continue;
                    }
                    $element = $html->find('div[class=l-vacancy] div[class=vacancy-section]')[0]->find('div.text');
                    $text = $element[0]->innertext;
                    $text = strip_tags($text);
                    $text = trim($text);

                    $date = $html->find('div[class=date]');
                    $date = $date[0]->innertext;
                    $addDate = $generateDateInfo->newFormatDateAsRussianMonth($date);

                    $cities = $html->find('div[class=sh-info] span.place');
                    if($this->checkKey($cities,0)) {
                        $cities = $cities[0]->innertext;
                        $cities = trim($cities);
                        $cities = explode(',', $cities);
                    }

                        foreach ($cities as $city) {
                            if($city==''){
                                $city='Город неизвестен';
                            }
                            $city = trim($city);
                            $db->insertCities($vacancyId, $city, $tableNameCities);
                        }
                    $db->insertTags($vacancyId, $tag, $tableNameTags);
                    $db->insertVacancyInfo($vacancyId, $text, $addDate, $tableNameVacancyInfo);

                }
            }

        }
    }
}
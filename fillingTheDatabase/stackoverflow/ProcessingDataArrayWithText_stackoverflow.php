<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/BD/WorkWithDB.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/abstractClass/ProcessingDataArrayWithText.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/stackoverflow/ProcessingWithDate_stackoverflow.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/general/ProcessingWithCity.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/general/ProcessingWithTableNameAndFields.php';


class ProcessingDataArrayWithText_stackoverflow extends ProcessingDataArrayWithText
{
    protected function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray)
    {

        if (!empty($idAndLinksAndMayNotBeCompleteTextArray)) {

            $db = WorkWithDB::getInstance();
            $generateDateInfo = new ProcessingWithDate_stackoverflow();

            $processingWithCity = new ProcessingWithCity();

            $processingWithTableNameAndField = new ProcessingWithTableNameAndField();
            $tableNameCities = $processingWithTableNameAndField->generateCitiesTableName(__CLASS__);
            $tableNameVacancyInfo = $processingWithTableNameAndField->generateVacancyInfoTableName(__CLASS__);
            $tableNameTags = $processingWithTableNameAndField->generateTagsTableName(__CLASS__);

            foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
                if ($idAndTextAndLinksMap['text'] == null) {
                    $dateAdd = $generateDateInfo->newFormatDate($idAndTextAndLinksMap['dateAdd']);

                    $tags = $idAndTextAndLinksMap['tags'];

                    $html = file_get_html($idAndTextAndLinksMap['linkToJob']);
                    usleep(100000);

                    if ($html == FALSE) {
                        continue;
                    }

                    $element = $html->find('div[class=jobdetail] div');
                    $text = $element[3]->innertext;
                    $text = strip_tags($text);

                    $city = $html->find('span.location')[0]->innertext;
                    $city = $processingWithCity->parseCityFromStringStackoverflow($city);

                    if($tags==null){
                        $tags = array($idAndTextAndLinksMap['searchTag']);
                    }

                    foreach ($tags as $tag) {
                        $db->insertTags($vacancyId, $tag, $tableNameTags);
                    }

                    $db->insertCities($vacancyId, $city, $tableNameCities);
                    $db->insertVacancyInfo($vacancyId, $text, $dateAdd, $tableNameVacancyInfo);

                }
            }
        }
    }

}
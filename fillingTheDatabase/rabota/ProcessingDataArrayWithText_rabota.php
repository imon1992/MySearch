<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT . '/Search/BD/WorkWithDB.php';
include_once DOCUMENT_ROOT . '/Search/abstractClass/ProcessingDataArrayWithText.php';
include_once DOCUMENT_ROOT . '/Search/rabota/CurlInit_rabota.php';
include_once DOCUMENT_ROOT . '/Search/lib/simpl/simple_html_dom.php';
include_once DOCUMENT_ROOT . '/Search/rabota/ProcessingWithDate_rabota.php';
include_once DOCUMENT_ROOT . '/Search/general/ProcessingWithTableNameAndFields.php';
class ProcessingDataArrayWithText_rabota extends ProcessingDataArrayWithText
{
    function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray)
    {
        $db = WorkWithDB::getInstance();
        $generateDateInfo = new ProcessingWithDate_rabota();

        $processingWithTableNameAndField = new ProcessingWithTableNameAndField();
        $tableNameCities  = $processingWithTableNameAndField->generateCitiesTableName(__CLASS__);
        $tableNameVacancyInfo = $processingWithTableNameAndField->generateCitiesTableName(__CLASS__);
        foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {

            if ($idAndTextAndLinksMap['text'] == null) {
                $dateAdd = $generateDateInfo->newFormatDate($idAndTextAndLinksMap['dateAdd']);
                $searchTag = $idAndTextAndLinksMap['searchTag'];
                $city = $idAndTextAndLinksMap['city'];


                $curlInit = new CurlInit_rabota();
                $curlResult = $curlInit->getCurlInit($idAndTextAndLinksMap['linkToJob']);

                $html = new simple_html_dom();
                $html->load($curlResult);
                usleep(100000);

                if ($html == FALSE) {
                    continue;
                }

                foreach ($html->find('#beforeContentZone_vcVwPopup_VacancyViewInner1_pnlBody') as $elements) {
                    $text = $elements->innertext;
                }

                $db->insertCities($vacancyId, $city,$tableNameCities);

                $db->insertVacancyInfo($vacancyId, $text, $dateAdd, $searchTag,$tableNameVacancyInfo);

            }

        }

    }
}
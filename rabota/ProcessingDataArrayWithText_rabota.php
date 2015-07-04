<?php
include_once '../BD/WorkWithDB.rabota.php';
include_once '../abstractClass/ProcessingDataArrayWithText.php';
include_once 'CurlInit_rabota.php';
include_once '../lib/simpl/simple_html_dom.php';

class ProcessingDataArrayWithText_rabota extends ProcessingDataArrayWithText
{
    function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray)
    {
        $db = WorkWithDB2::getInstance();
        foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            if ($idAndTextAndLinksMap['text'] == null) {

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


                $db->insertData($vacancyId, $text, $idAndTextAndLinksMap['companyId']);
                $idAndLinksAndMayNotBeCompleteTextArray[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
            unset($idAndLinksAndMayNotBeCompleteTextArray[$vacancyId]['companyId']);
        }

        return $idAndLinksAndMayNotBeCompleteTextArray;
    }

}
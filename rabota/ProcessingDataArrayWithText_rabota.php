<?php
include_once '../BD/WorkWithDB.rabota.php';
include_once '../abstractClass/ProcessingDataArrayWithText.php';
include_once 'CurlInit_rabota.php';
include_once '../lib/simpl/simple_html_dom.php';
include_once 'generateDateInfo.php';
class ProcessingDataArrayWithText_rabota extends ProcessingDataArrayWithText
{
    function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray)
    {
        $db = WorkWithDB2::getInstance();
        $generateDateInfo = new GenerateDateInfo_rabota();

        foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            $dateInfo = $generateDateInfo->dateInfo($idAndTextAndLinksMap['dateAdd']);
            $timeInterval = $dateInfo[0];
            var_dump($timeInterval);
            $daysOrWeeks = $dateInfo[1];
            var_dump($daysOrWeeks);
//            var_dump()
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
echo '4to-to';
                if ($timeInterval == 'now') {
                    echo 'now';
                    $db->insertDataWithNowDate($vacancyId, $text, $idAndTextAndLinksMap['companyId']);
                } else {
                    echo 'else';
                    $db->insertDataWithDate($vacancyId, $text, $idAndTextAndLinksMap['companyId'], $timeInterval, $daysOrWeeks);
                }

                $idAndLinksAndMayNotBeCompleteTextArray[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
            unset($idAndLinksAndMayNotBeCompleteTextArray[$vacancyId]['companyId']);
        }

        return $idAndLinksAndMayNotBeCompleteTextArray;
    }

}
<?php
include_once '../BD/WorkWithDB.rabota.php';
include_once '../abstractClass/ProcessingDataArrayWithText.php';
include_once 'CurlInit_rabota.php';
include_once '../lib/simpl/simple_html_dom.php';
include_once 'generateDateInfo.php';
class ProcessingDataArrayWithText_rabota extends ProcessingDataArrayWithText
{
    protected function takeTheMissingText($idVacanciesLinksIdCompanyDateAddAndMayNotBeCompleteTextArray)
    {
        $db = WorkWithDB2::getInstance();
        $generateDateInfo = new GenerateDateInfo_rabota();

        foreach ($idVacanciesLinksIdCompanyDateAddAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            if ($idAndTextAndLinksMap['text'] == null) {

            $dateInfo = $generateDateInfo->dateInfo($idAndTextAndLinksMap['dateAdd']);
            $timeInterval = $dateInfo[0];
            $daysOrWeeks = $dateInfo[1];


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
                if ($timeInterval == 'now') {
                    $db->insertDataWithNowDate($vacancyId, $text, $idAndTextAndLinksMap['companyId']);
                } else {
                    $db->insertDataWithDate($vacancyId, $text, $idAndTextAndLinksMap['companyId'], $timeInterval, $daysOrWeeks);
                }

                $vacanciesIdAndText[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
        }

        return $vacanciesIdAndText;
    }

}
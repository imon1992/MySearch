<?php
include_once '../BD/WOrkWithDB.stackoverflow.class.php';
include_once '../abstractClass/ProcessingDataArrayWithText.php';
include_once 'generateDateInfo.php';
include_once '../lib/simpl/simple_html_dom.php';

class ProcessingDataArrayWithText_stackoverflow extends ProcessingDataArrayWithText
{
    protected function takeTheMissingText($idVacanciesLinksDateAddMayNotBeCompleteTextArray)
    {
        $db = WorkWithDB1::getInstance();
        $generateDateInfo = new GenerateDateInfo_stackoverflow();

        foreach ($idVacanciesLinksDateAddMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            if ($idAndTextAndLinksMap['text'] == null) {
                $dateInfo = $generateDateInfo->dateInfo($idAndTextAndLinksMap['dateAdd']);
                $timeInterval = $dateInfo[0];
                $daysOrWeeks = $dateInfo[1];

                $html = file_get_html($idAndTextAndLinksMap['linkToJob']);
                usleep(100000);

                if ($html == FALSE) {
                    continue;
                }
                $element = $html->find('div[class=jobdetail] div');
                $text = $element[3]->innertext;

                if ($timeInterval == 'now') {
                    $db->insertDataWithNowDate($vacancyId, $text);
                } else {
                    $db->insertDataWithDate($vacancyId, $text, $timeInterval, $daysOrWeeks);
                }
                $idVacanciesMayNotBeCompleteTextArray[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
        }

        return $idVacanciesMayNotBeCompleteTextArray;
    }

}
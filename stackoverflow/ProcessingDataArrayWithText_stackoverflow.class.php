<?php
include_once '../BD/WorkWithDB.stackoverflow.class.php';
//include_once '../simpl/simple_html_dom.php';

class ProcessingDataArrayWithText_stackoverflow
{

    function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray)
    {

        $db = WorkWithDB1::getInstance();
        foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            if ($idAndTextAndLinksMap['text'] == null) {

                $html = file_get_html($idAndTextAndLinksMap['linkToJob']);
                usleep(100000);

                if ($html == FALSE) {
                    continue;
                }
                $element = $html->find('div[class=jobdetail] div');
                $text = $element[3];

                $db->insertData($vacancyId, $text);
                $idAndLinksAndMayNotBeCompleteTextArray[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
        }

        return $idAndLinksAndMayNotBeCompleteTextArray;
    }

}

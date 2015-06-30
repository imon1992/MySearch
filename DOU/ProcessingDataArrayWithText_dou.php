<?php

include_once '../abstractClass/ProcessingDataArrayWithText.php';
include_once '../BD/WorkWithDB.DOU.class.php';

class ProcessingDataArrayWithText_dou extends ProcessingDataArrayWithText
{
    function takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray)
    {

        $db = WorkWithDB::getInstance();
        foreach ($idAndCompaniesAndMayNotBeCompleteTextArray as $vacancyId => $idAndCompanyAndTextMap) {
            if ($idAndCompanyAndTextMap['text'] == null) {
                $companyName = $idAndCompanyAndTextMap['company'];

                $http = "http://jobs.dou.ua/companies/$companyName/vacancies/$vacancyId/";

                usleep(100000);                                                                 //микросекунды;


                $html = file_get_html($http);

                if ($html == FALSE) {
                    continue;
                }
                $element = $html->find('div[class=l-vacancy]');

                $text = $element[0]->innertext;
                $db->insertData($vacancyId, $text);
                $idAndCompaniesAndMayNotBeCompleteTextArray[$vacancyId] = array('vacantionsId' => $vacancyId,
                    'company' => $idAndCompanyAndTextMap['company'],
                    'text' => $text);
            }
        }

        return $idAndCompaniesAndMayNotBeCompleteTextArray;
    }

}
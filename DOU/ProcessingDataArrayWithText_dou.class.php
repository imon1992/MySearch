<?php
header("Content-Type: text/html; charset=utf-8");
//принимает маасив с текстом или без него, если текста нету(для данной компании),делает запросс на страницу и добавляет текст в базу
include_once '../BD/WorkWithDB.DOU.class.php';
//include_once 'simpl/simple_html_dom.php';
class ProcessingDataArrayWithText_dou {
//    function takeTheMissingTextInLinks($idAndCompaniesAndMayNotBeCompleteTextArray){
//
//    }

    function takeTheMissingText($idAndCompaniesAndMayNotBeCompleteTextArray) {

        $db = WorkWithDB::getInstance();
        foreach ($idAndCompaniesAndMayNotBeCompleteTextArray as $vacancyId => $idAndCompanyAndTextMap) {
            if ($idAndCompanyAndTextMap['text'] == null) {
                $companyName = $idAndCompanyAndTextMap['company'];

                $http = "http://jobs.dou.ua/companies/$companyName/vacancies/$vacancyId/";
//                var_dump($http);
                usleep(100000);                                                                 //микросекунды;


                $html = file_get_html($http);

                if ($html == FALSE) {
                    continue;
                }
                $element = $html->find('div[class=l-vacancy]');   // вытаскивает всю страницу т.к body имеет класс b-vacancy-page,нужно подругому реализовать поиск
                                                           // по содержимому файла, т.е c взятой информации с базы, вытащить нужный елемент и в нем искать.

                $text = $element[0]->innertext;
                $db->insertData($vacancyId, $text);
                $idAndCompaniesAndMayNotBeCompleteTextArray[$vacancyId] = array('vacantionsId' => $vacancyId,
                    'company' => $idAndCompanyAndTextMap['company'],
                    'text' => $text);
            }
        }
//        echo"<pre>";
//print_r($idAndCompaniesAndMayNotBeCompleteTextArray);
        return $idAndCompaniesAndMayNotBeCompleteTextArray;
    }

}
//header("Content-Type: text/html; charset=utf-8");
//$c = new ProcessingDataArrayWhithText();
//$x = $c->takeTheMissingText(array(10650=>array('company'=>'mobidev','id_vacancies'=>10650,'text'=>null)));
//
//
//echo"<pre>";
//print_r($x);
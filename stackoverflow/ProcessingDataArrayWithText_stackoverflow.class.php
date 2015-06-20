<?php
//header("Content-Type: text/html; charset=utf-8");
//принимает маасив с текстом или без него, если текста нету(для данной компании),делает запросс на страницу и добавляет текст в базу
include_once '../BD/WorkWithDB.stackoverflow.class.php';
include_once '../simpl/simple_html_dom.php';
class ProcessingDataArrayWithText_stackoverflow {
//    function takeTheMissingTextInLinks($idAndLinksAndMayNotBeCompleteTextArray){
//
//    }

    function takeTheMissingText($idAndLinksAndMayNotBeCompleteTextArray) {

        $db = WorkWithDB1::getInstance();
        foreach ($idAndLinksAndMayNotBeCompleteTextArray as $vacancyId => $idAndTextAndLinksMap) {
            if ($idAndTextAndLinksMap['text'] == null) {
//                var_dump($http);

//                $html = new simple_html_dom();
                $html = file_get_html($idAndTextAndLinksMap['linkToJob']);
                usleep(100000);                                                                 //микросекунды;

                if ($html == FALSE) {
                    continue;
                }
                $element = $html->find('div[class=jobdetail] div');   // вытаскивает всю страницу т.к body имеет класс b-vacancy-page,нужно подругому реализовать поиск
                // по содержимому файла, т.е c взятой информации с базы, вытащить нужный елемент и в нем искать.
//var_dump($element);
//                echo $element;
                $text = $element[3];
//                echo $text;
//                var_dump($text);
                $db->insertData($vacancyId, $text);
                $idAndLinksAndMayNotBeCompleteTextArray[$vacancyId] = array('vacationsId' => $vacancyId,
                    'text' => $text);
            }
        }
//        echo"<pre>";
//print_r($idAndLinksAndMayNotBeCompleteTextArray);
        return $idAndLinksAndMayNotBeCompleteTextArray;
    }

}
//header("Content-Type: text/html; charset=utf-8");
//$c = new ProcessingDataArrayWhithText();
//$x = $c->takeTheMissingText(array(10650=>array('company'=>'mobidev','id_vacancies'=>10650,'text'=>null)));
//
//
//echo"<pre>";
//print_r($x);
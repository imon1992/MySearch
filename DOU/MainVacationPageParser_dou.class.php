<?php

//класс возвращяет ссылки с вакансиями
//include_once '../simpl/simple_html_dom.php';

class MainVacationPageParser_dou {

    function parseFirstPart($url) {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "count=0&csrfmiddlewaretoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm");
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=16e61c63986cc981:T=1412706042:S=ALNI_MaOyUGB7e9rZQHHjEP_YdImdbAfyA; __utmt=1; csrftoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm; __utma=15214883.1329840483.1412706043.1425585907.1425592692.26; __utmb=15214883.18.10.1425592692; __utmc=15214883; __utmz=15214883.1425376018.14.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided);");
            $out = curl_exec($curl);
            curl_close($curl);
            preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $out, $linksToJobs);
        }
        return $linksToJobs;
    }

    function parseNextPart($searchTagAndCity) {
        $searchTag = $searchTagAndCity[0];
        $city = $searchTagAndCity[1];
        if($city === false) {
            $url = 'http://jobs.dou.ua/vacancies/?search=' . $searchTag;
        }else{$url ='http://jobs.dou.ua/vacancies/?city=' . $city . '&search=' . $searchTag;  }
//        $position = stripos($url, '=');
//        $lengthURL = strlen($url);
//        $languageName = substr($url, $position + 1, $lengthURL);
//        $beginningCityPosition = stripos($url, 'city=');
//        if ($beginningCityPosition !== false) {
//            $newLine = substr($url, $beginningCityPosition + 5, $lengthURL); //cтрока типа "город&search=язык"
//            $searchPosition = stripos($newLine, '&search');
//            $lengthNewLine = strlen($newLine);
//            $cityName = substr($newLine, -$lengthNewLine, $searchPosition);  //строка типа "Город"
//        }
        $html = file_get_html($url);
        foreach ($html->find('div.b-vacancies-head h1') as $element) {
            $arrayReferencesVacancies[] = $element->innertext;
        }
        preg_match_all("/\d+/", $arrayReferencesVacancies[0], $numberOfVacancies);
        $numberOfVacancies = $numberOfVacancies[0][0];
        if ($numberOfVacancies < 20) {
            $numberOfIterations = 0;
        } else {
            $numberOfIterations = ceil(($numberOfVacancies - 20) / 40);
        }

        //$numberOfIterations количество итераций в цыкле на запрос
        $firstPartJobs = $this->parseFirstPart($url);
        foreach ($firstPartJobs[0] as $element) {
            $firstArray[] = $element;
        }

        for ($nextVacancies = 20; $nextVacancies <= ($numberOfIterations * 40) + 20; $nextVacancies+=40) {

            $curl = curl_init();
//            if ($beginningCityPosition !== false) {
//                curl_setopt($curl, CURLOPT_URL, "http://jobs.dou.ua/vacancies/xhr-load/?city=$cityName&search=$languageName");
                curl_setopt($curl, CURLOPT_URL, $url);
//            } else {
//                curl_setopt($curl, CURLOPT_URL, "http://jobs.dou.ua/vacancies/xhr-load/?search=$languageName");
//            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "count=$nextVacancies&csrfmiddlewaretoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm");
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=16e61c63986cc981:T=1412706042:S=ALNI_MaOyUGB7e9rZQHHjEP_YdImdbAfyA; __utmt=1; csrftoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm; __utma=15214883.1329840483.1412706043.1425585907.1425592692.26; __utmb=15214883.18.10.1425592692; __utmc=15214883; __utmz=15214883.1425376018.14.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided);");
            $out = curl_exec($curl);
            curl_close($curl);
            preg_match_all("/http\:\/\/jobs\.dou\.ua\/companies\/([\w-]+)\/vacancies\/\d+\//", $out, $secondPartJobs);
            foreach ($secondPartJobs[0] as $element) {
                $secondArray[] = $element;
            }
        }

        if ($numberOfVacancies > 20) {
            $linksToJobs = array_merge($firstArray, $secondArray);
        } else {
            $linksToJobs = $firstArray;
        }
        return $linksToJobs;
    }

}
//$c = new MainVacantionPageParser();
//$x =$c->parseNextPart('http://jobs.dou.ua/vacancies/?city=%D0%9D%D0%B8%D0%BA%D0%BE%D0%BB%D0%B0%D0%B5%D0%B2&search=PHP');
//echo '<pre>';
//print_r($x);
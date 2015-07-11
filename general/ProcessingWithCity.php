<?php

class ProcessingWithCity{
//    function generateCityAndSearchTag($objectWithCityAndSearchTag){
//        if($objectWithCityAndSearchTag->city != null){
//            $city = $objectWithCityAndSearchTag->city;
//        }else{
//            $city = null;
//        }
////
//        return $city;
//    }
function parseCityFromStringRabota($string){
//    var_dump($string);
    preg_match('/<\/i>([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix',$string,$cityWithTag);
//    var_dump($cityWithTag[0]);
    preg_match('/>([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix',$cityWithTag[0],$cityWithTagPart);
//    var_dump($cityWithTagPart);
    preg_match('/([A-Za-zабвгдґеёєжзийіїклмнопрстуфхчцшщъьыэюяАБВГҐДЕЁЄЖЗИЙІЇКЛМНОПРСТУФХЧЦШЩЪЬЫЭЮЯ -])+/ix',$cityWithTagPart[0],$city);
    $city = trim($city[0]);
    return $city;
}
    function parseCityFromStringStackoverflow($string){
        preg_match('([A-Za-zА-Яа-я0-9&#; -])+/ix',$string,$city);
//        preg_match('/;([A-Za-zА-Яа-я -])+/ix',$s[0],$cityWithTagPart);
//        preg_match('/([A-Za-zА-Яа-я -])+/ix',$cityWithTagPart[0],$city);
$city = trim($city[0]);
    return $city;
}
}
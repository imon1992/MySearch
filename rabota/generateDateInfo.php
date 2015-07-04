<?php

class GenerateDateInfo_rabota{
    function dateInfo($timeSpan){
        if(strpos($timeSpan, 'часов')){
            $timeInterval = 'now';
        }elseif(strpos($timeSpan, 'день')){
            $timeInterval = 'DAY';
            $daysOrWeeks = 1;
        }elseif(strpos($timeSpan, 'дней')||strpos($timeSpan, 'дня')){
            $timeInterval = 'DAY';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }elseif(strpos($timeSpan, 'неделю')){
            $timeInterval = 'WEEK';
            $daysOrWeeks = 1;
        }elseif(strpos($timeSpan, 'недели')){
            $timeInterval = 'WEEK';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }else{
            $timeInterval = 'now';
        }
    return [$timeInterval,$daysOrWeeks];
    }
}
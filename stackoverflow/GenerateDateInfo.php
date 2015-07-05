<?php

class GenerateDateInfo_stackoverflow{
    function dateInfo($timeSpan){
        if(strpos($timeSpan, 'yesterday')){
            $timeInterval = 'DAY';
            $daysOrWeeks = 1;
        }elseif(strpos($timeSpan, 'days')){
            $timeInterval = 'DAY';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }elseif(strpos($timeSpan, 'week')||strpos($timeSpan, 'weeks')){
            $timeInterval = 'WEEK';
            preg_match("/\d+/", $timeSpan, $dayORWeeksArray);
            $daysOrWeeks =  $dayORWeeksArray[0];
        }else{
            $timeInterval = 'now';
        }
        return [$timeInterval,$daysOrWeeks];
    }
}
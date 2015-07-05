<?php

class GenerateDataInfo_dou
{
    public function newFormatDate($date)
    {

        $date = str_replace(array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'),
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            $date);
        $date = trim($date);
        return date("Y.m.d", strtotime($date));
    }
}
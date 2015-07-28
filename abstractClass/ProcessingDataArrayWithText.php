<?php

abstract class ProcessingDataArrayWithText{
    abstract protected function takeTheMissingText($vacancyInfo);
    public function getTheMissingText($vacancyInfo){
        return $this->takeTheMissingText($vacancyInfo);
    }
    public function checkKey($array,$key){
        return array_key_exists($key, $array) ? $array[$key]: null;
    }
}
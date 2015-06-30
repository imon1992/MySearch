<?php

abstract class ProcessingDataArrayWithText{
    abstract protected function takeTheMissingText($vacancyInfo);
    public function getTheMissingText($vacancyInfo){
        return $this->takeTheMissingText($vacancyInfo);
    }
}
<?php

abstract class MainVacationPageParser {
    abstract protected function generateAllLinks($dataForTheGenerateLinks);

    public function getAllLinks($dataForTheGenerateLinks) {

        return $this->generateAllLinks($dataForTheGenerateLinks);

    }
    protected function changSumSymbols($symbol){
        $symbol = str_replace(' ','%20',$symbol);
        $symbol = $tag = str_replace('+','%2B',$symbol);
        $symbol = $tag = str_replace('#','%23',$symbol);
        return $symbol;
    }
}
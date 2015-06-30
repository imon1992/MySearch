<?php

abstract class MainVacationPageParser {
    abstract protected function generateAllLinks($dataForTheGenerateLinks);

    public function getAllLinks($dataForTheGenerateLinks) {

        return $this->generateAllLinks($dataForTheGenerateLinks);

    }
}
<?php

abstract class CurlInit {
    abstract protected function curlInit1($url,$nextNumberOfVacation = false);

    public function getCurlInit($url,$nextNumberOfVacation = false) {

        return $this->curlInit1($url,$nextNumberOfVacation);

    }
}
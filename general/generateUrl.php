<?php

class GenerateUrl{
    public function generateUrlFirstPageDou($searchTag,$city = null){
        if ($city == null) {
            if ($searchTag != 'beginners') {
                $url = 'http://jobs.dou.ua/vacancies/?search=' . $searchTag;
//                echo 'i == beginners';
            } else {
                $url = 'http://jobs.dou.ua/vacancies/?beginners';
            }
        } else {
            if ($city == 'удаленная работа') {
                $url = 'http://jobs.dou.ua/vacancies/?remote&search=' . $searchTag;
            } else if ($city == 'работа за рубежом') {
                $url = 'http://jobs.dou.ua/vacancies/?relocation&search=' . $searchTag;
            } else {
                $url = 'http://jobs.dou.ua/vacancies/?city=' . $city . '&search=' . $searchTag;
            }
        }
        return $url;
    }

    public function generateUrlNextPartDou($searchTag,$city=null){
        if ($city != null) {
            if ($city == 'удаленная работа') {
                $url = 'http://jobs.dou.ua/vacancies/xhr-load/?remote&search=' . $searchTag;
            } else if ($city == 'работа за рубежом') {
                $url = 'http://jobs.dou.ua/vacancies/xhr-load/?relocation&search=' . $searchTag;
            } else {
                $url = 'http://jobs.dou.ua/vacancies/xhr-load/?city' . $city . '&search=' . $searchTag;
            }

        } else {
            if ($searchTag != 'beginners') {
                $url = 'http://jobs.dou.ua/vacancies/xhr-load/?search=' . $searchTag;
            } else {
                $url = 'http://jobs.dou.ua/vacancies/xhr-load/?beginners';
            }
        }
        return $url;
    }

    public function generateUrlFirstPageStackoverflow($searchTag){
        $url = 'http://careers.stackoverflow.com/jobs?searchTerm=' . $searchTag;
        return $url;
    }

    public function generateUrlLastPageStackoverflow($searchTag,$lastPageNumber){
        $url = 'http://careers.stackoverflow.com/jobs?searchTerm=' . $searchTag. "&pg=$lastPageNumber";
        return $url;
    }

    public function generateUrlFirstPageRabota($searchTag){
        $url = 'http://rabota.ua/jobsearch/vacancy_list?keywords=' . $searchTag;
        return $url;
    }

    public function generateUrlLastPageRabota($searchTag,$lastPageNumber){
        $url = 'http://rabota.ua/jobsearch/vacancy_list?keywords=' . $searchTag . "&pg=$lastPageNumber";
        return $url;
    }
}
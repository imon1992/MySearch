<?php
require_once '../DOU/SearchQuery_dou.class.php';
require_once '../stackoverflow/SearchQuery_stackoverflow.class.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchData'])) {

    $json = $_POST['searchData'];
    $searchParams = json_decode($json);
    $whereAndWhatSearchArray = array_shift($searchParams);
    $whereAndWhatSearchArrayLength =sizeof($whereAndWhatSearchArray);
    if($whereAndWhatSearchArrayLength == 2) {
//
        $searchQuery = new searchQuery_dou();
        $searchResponse = $searchQuery->search($whereAndWhatSearchArray, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
    if($whereAndWhatSearchArrayLength == 1) {
        $searchTag = $whereAndWhatSearchArray[0];
        $searchQuery = new SearchQuery_stakoverflow();
        $searchResponse = $searchQuery->search($searchTag, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
}


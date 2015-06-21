<?php
require_once '../DOU/SearchQuery_dou.class.php';
require_once '../stackoverflow/SearchQuery_stackoverflow.class.php';
include_once '../BD/WorkWithDB.DOU.class.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchData'])) {

    $json = $_POST['searchData'];
    $searchParams = json_decode($json);
    $whereAndWhatSearchArray = array_shift($searchParams);
    $whereAndWhatSearchArrayLength = sizeof($whereAndWhatSearchArray);
    if ($whereAndWhatSearchArrayLength == 2) {
//
        $searchQuery = new searchQuery_dou();
        $searchResponse = $searchQuery->search($whereAndWhatSearchArray, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
    if ($whereAndWhatSearchArrayLength == 1) {
        $searchTag = $whereAndWhatSearchArray[0];
        $searchQuery = new SearchQuery_stakoverflow();
        $searchResponse = $searchQuery->search($searchTag, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['tag'])) {
    $tag = $_GET['tag'];
    if(strpos($tag,' ')){
        $tag = str_replace(' ','%20',$tag);
    }else if(strpos($tag,'+')){
        $tag = str_replace('+','%2B',$tag);
    }
//    var_dump($tag);
    $db = WorkWithDB::getInstance();
$towns = $db->getTowns($tag);
    $towns = json_encode($towns);
    echo $towns;
}


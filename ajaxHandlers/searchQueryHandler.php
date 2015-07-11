<?php
//header("Content-Type: text/html; charset=utf-8");
//require_once '../DOU/SearchQuery_dou.class.php';
//require_once '../stackoverflow/SearchQuery_stackoverflow.class.php';
require_once '../BD/WorkWithDB.DOU.class.php';
require_once '../DOU/SearchQuery_dou.php';
require_once '../stackoverflow/SearchQuery_stackoverflow.php';
require_once '../rabota/SearchQuery_rabota.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchData'])) {
//
    $json = $_POST['searchData'];
    $searchParams = json_decode($json);
    $whereAndWhatSearchObject = array_shift($searchParams);
    $whereAndWhatSearchObjectLength = sizeof($whereAndWhatSearchObject);

    if ($whereAndWhatSearchObject->site == '?dou'){
        $searchQuery = new SearchQuery_dou();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
    if ($whereAndWhatSearchObject->site == '?stackoverflow') {
//        $searchTag = $whereAndWhatSearchObject->searchTag;
        $searchQuery = new SearchQuery_stackoverflow();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }

    if ($whereAndWhatSearchObject->site == '?rabota') {
//        $searchTag = $whereAndWhatSearchObject->searchTag;
        $searchQuery = new SearchQuery_rabota();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
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

//[{"searchTag":"PHP","site":"?dou","city":"Николаев","withCityOrNot":true},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]
<?php
//header("Content-Type: text/html; charset=utf-8");
//require_once '../DOU/SearchQuery_dou.class.php';
//require_once '../stackoverflow/SearchQuery_stackoverflow.class.php';
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once '../BD/WorkWithDB.php';
require_once '../DOU/SearchQuery_dou.php';
require_once '../stackoverflow/SearchQuery_stackoverflow.php';
require_once '../rabota/SearchQuery_rabota.php';
include_once DOCUMENT_ROOT.'/Search/general/ProcessingWithCity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchData'])) {
//
    $json = $_POST['searchData'];
    $searchParams = json_decode($json);
    $whereAndWhatSearchObject = array_shift($searchParams);
    $whereAndWhatSearchObjectLength = sizeof($whereAndWhatSearchObject);

    if ($whereAndWhatSearchObject->site == '?dou') {
        $searchQuery = new SearchQuery_dou();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
    if ($whereAndWhatSearchObject->site == '?stackoverflow') {
        $searchQuery = new SearchQuery_stackoverflow();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }

    if ($whereAndWhatSearchObject->site == '?rabota') {
        $searchQuery = new SearchQuery_rabota();
        $searchResponse = $searchQuery->getSearch($whereAndWhatSearchObject, $searchParams);
        $searchResponse = json_encode($searchResponse);
        echo $searchResponse;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['tag'])) {
    if ($_GET['site'] == '?dou') {
        $tag = $_GET['tag'];
        $site = $_GET['site'];
        $processingWithCity = new ProcessingWithCity();
        $cities = $processingWithCity->getCities($tag, $site);
    }

    $cities = json_encode($cities);
    echo $cities;
}

//[{"searchTag":"PHP","site":"?dou","city":"Николаев","withCityOrNot":true},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]
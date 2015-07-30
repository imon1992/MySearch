<?php
//header("Content-Type: text/html; charset=utf-8");
//require_once '../DOU/SearchQuery_dou.class.php';
//require_once '../stackoverflow/SearchQuery_stackoverflow.class.php';
////define("$_SERVER['DOCUMENT_ROOT']", $_SERVER['$_SERVER['DOCUMENT_ROOT']']);
require_once '../BD/WorkWithDB.php';
require_once '../DOU/SearchQuery_dou.php';
require_once '../stackoverflow/SearchQuery_stackoverflow.php';
require_once '../rabota/SearchQuery_rabota.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/general/ProcessingWithCity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && checkKey($_POST, 'searchData')) {

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

if ($_SERVER['REQUEST_METHOD'] == 'GET' && checkKey($_GET, 'tag')) {
    $tag = $_GET['tag'];
    if ($_GET['site'] == '?dou') {
        $site = $_GET['site'];
        $processingWithCity = new ProcessingWithCity();
        $cities = $processingWithCity->getCitiesDou($tag, $site);
        $cities = json_encode($cities);
        echo $cities;
    }
    if ($_GET['site'] == '?stackoverflow') {
        $site = $_GET['site'];
        $processingWithCity = new ProcessingWithCity();
        $cities = $processingWithCity->getCitiesStackoverflowRabota($tag, $site);
        $cities = json_encode($cities);
        echo $cities;
    }
    if ($_GET['site'] == '?rabota') {
        $site = $_GET['site'];
        $processingWithCity = new ProcessingWithCity();
        $cities = $processingWithCity->getCitiesStackoverflowRabota($tag, $site);
        $cities = json_encode($cities);
        echo $cities;
    }

}
function checkKey($array, $key)
{
    return array_key_exists($key, $array) ? $array[$key] : null;
}
//searchData=[{"searchTag":"php","site":"?stackoverflow","city":"Berlin"},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]
//[{"searchTag":"PHP","site":"?dou","city":"Николаев","withCityOrNot":true},{"name":"php","search":[{"name":"php"}],"notPresented":[{}]}]
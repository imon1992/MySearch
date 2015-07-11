<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/CurlInit.php';

class CurlInit_stackoverflow extends CurlInit
{
    protected function curlInit1($url, $nextNumberOfVacation = false)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=16e61c63986cc981:T=1412706042:S=ALNI_MaOyUGB7e9rZQHHjEP_YdImdbAfyA; __utmt=1; csrftoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm; __utma=15214883.1329840483.1412706043.1425585907.1425592692.26; __utmb=15214883.18.10.1425592692; __utmc=15214883; __utmz=15214883.1425376018.14.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided);");
            $curlResult = curl_exec($curl);
            curl_close($curl);
        }
        return $curlResult;
    }
}
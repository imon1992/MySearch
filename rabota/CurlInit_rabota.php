<?php
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
include_once DOCUMENT_ROOT.'/Search/abstractClass/CurlInit.php';

class CurlInit_rabota extends CurlInit
{
    protected function curlInit1($url, $nextNumberOfVacation = false)
    {
    $header = [
'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
'Cache-Control:max-age=0',
'Connection:keep-alive',
'Host:rabota.ua',
'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36'];
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=a99f7b7a1394f611:T=1431337609:S=ALNI_MbCo2s3Rs6rx3DyERs-Tj69gCt2Dw; hideTopInfo=1; csrftoken=ULrxVs7HzTtJ6g8Sb2KzQT6S7oK4q3A3; __utmt=1; __utma=15214883.995779835.1431337606.1435910752.1435938076.56; __utmb=15214883.1.10.1435938076; __utmc=15214883; __utmz=15214883.1435756862.51.4.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)");
            $curlResult = curl_exec($curl);
            curl_close($curl);
        }
        return $curlResult;
    }
}
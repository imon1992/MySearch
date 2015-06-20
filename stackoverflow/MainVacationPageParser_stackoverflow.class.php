<?php
//namespace stackoverflow;
include_once '../simpl/simple_html_dom.php';

class MainVacationPageParser_stackoverflow
{
    function linksParse($url, $tag)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "count=0&csrfmiddlewaretoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm");
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=16e61c63986cc981:T=1412706042:S=ALNI_MaOyUGB7e9rZQHHjEP_YdImdbAfyA; __utmt=1; csrftoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm; __utma=15214883.1329840483.1412706043.1425585907.1425592692.26; __utmb=15214883.18.10.1425592692; __utmc=15214883; __utmz=15214883.1425376018.14.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided);");
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $html = new simple_html_dom();
        $html->load($out);
        $fullLinksToJobs = array('linksToJob' => array(), 'endOfCycle' => 'true');
        foreach ($html->find('div[class=listResults -jobs list jobs]') as $element) {
            foreach ($element->find('div.listResults div.tags') as $tagsName) {
                if (strpos(strtolower($tagsName), strtolower($tag)) !== false) {
                    $partLinksToJob[] = $tagsName->parentNode()->childNodes(2)->childNodes(0)->href;
                } else {
                    $fullLinksToJobs['endOfCycle'] = false;
                    break 2;
//                    return false;
                }
            }
        }
        if ($partLinksToJob != null && is_array($partLinksToJob)) {
            foreach ($partLinksToJob as $linksPart) {
                $fullLinksToJobs['linksToJob'][] = 'http://careers.stackoverflow.com/' . $linksPart;
            }
        }
        return $fullLinksToJobs;
    }

    public function allLinks($searchTag)
    {
//        echo date('Y h:i:s A');
        $url = 'http://careers.stackoverflow.com/jobs?searchTerm=' . $searchTag;
        $html = file_get_html($url);
        foreach ($html->find('#index-hed h2 span') as $element) {
            preg_match("/\d+/", $element->innertext, $countOfVacancy);
        }
        $countOfVacancy = $countOfVacancy[0];
        $countOfPages = ceil($countOfVacancy / 25);
        for ($i = 1; $i <= $countOfPages; $i++) {
            if ($i == 1) {
                $urlWithPageNumber = $url;
            } else {
                $urlWithPageNumber = $url . "&pg=$i";
            }
            $linksToJob = $this->linksParse($urlWithPageNumber, $searchTag);
            if ($linksToJob != null && is_array($linksToJob))
                $allLinksToJob = array_merge((array)$allLinksToJob, $linksToJob['linksToJob']);
            if ($linksToJob['endOfCycle'] === false)
                break;
        }
//        echo date('Y h:i:s A');
        return $allLinksToJob;

    }
}

//$c = new MainVacationPageParser();
//$a = $c->allLinks( 'symfony2');
//////echo '<pre>';
//////print_r($a);
////$a = $c->linksParse('http://careers.stackoverflow.com/jobs?searchTerm=php&pg=2', 'php');
//echo '<pre>';
//print_r($a);
//
//include_once "ParserIdFromLinks.stackoverflow.class.php";
//$d = new ParserIdFromLinks();
//$s = $d->processingReferences($a);
//
////echo '<pre>';
////print_r($s);
////
//include_once "CacheGetter_stackoverflow.class.php";
//$h = new CacheGetter();
//$t = $h->formationMapWithText($s);
////
////echo '<pre>';
////print_r($t);
//
//include_once 'ProcessingDataArrayWithText_stackoverflow.class.php';
//
//$y = new ProcessingDataArrayWithText();
//$k = $y->takeTheMissingText($t);
//
//
//echo '<pre>';
//print_r($k);
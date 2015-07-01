<?php
include_once '../../lib/simpl/simple_html_dom.php';
include_once '../../BD/WorkWithDB.DOU.class.php';
class CitiesList{
    protected function getCities()
    {
        $sectionsArray = ['Java', 'Python', '.NET', 'Ruby', 'PHP', 'iOS/Mac', 'C%2B%2B', 'Android', 'QA', 'Front%20End', 'Project%20Manager', 'DevOps', 'beginners'];
        foreach ($sectionsArray as $section) {
            if($section =='beginners'){
                $url = 'http://jobs.dou.ua/vacancies/?beginners';
            }else {
                $url = 'http://jobs.dou.ua/vacancies/?search=' . $section;
            }
            $html = file_get_html($url);
            foreach ($html->find('ul.other li a') as $element) {
                $arraySections[] = array($section=>$element->innertext);
            }
        }
            return $arraySections;

    }

    public function addCitiesToDb(){
        $db = WorkWithDB::getInstance();
        $arraySections = $this->getCities();
        foreach($arraySections as $cities){
            foreach($cities as $key=>$city){
                $db->insertSectionAndTown($city,$key);
            }
        }
    }

}
//C++ = C%2B%2B
$c = new GetCities1();

$r = $c->getCities();
echo '<pre>';
print_r($r);
var_dump($r);

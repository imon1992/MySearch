<?php
include_once 'ConnectToDB.php';

class WorkWithDb{

    protected $_db;
    protected static $_instance;

    private function __construct() {
        $db = new ConnectToDB();
        $db->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->_db = $db;
    }

    public function __destruct() {
        unset($this->_db);
    }

    private function __clone() {

    }

    public static function getInstance() {
        // проверяем актуальность экземпляра
        if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance;
    }

    protected function db2Arr($data) {
        $arr = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $arr[] = $row;
        return $arr;
    }

    function getVacancyInfoByDate($from,$by,$tableName,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag){
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancy},{$tableFieldTextVacancy}
            FROM {$tableName}
            WHERE date_add BETWEEN :from AND :by
            AND search_tag = :searchTag
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':searchTag', $searchTag);
//        $stmt->bindParam(':tableName', $tableName);
        $stmt->execute();
        return $this->db2Arr($stmt);
    }

    function insertCities($vacancyId,$city,$tableName){
        $stmt = $this->_db->db->prepare(
            "INSERT INTO {$tableName} (id_vacancy,city)
                VALUES(:id_vacancy,:city)");
        $stmt->bindParam(':id_vacancy', $vacancyId);
        $stmt->bindParam(':city', $city);
//        $stmt->bindParam(':city', $city);
        $stmt->execute();
    }

    function insertVacancyInfo($idVacancies, $text,$dateAdd,$searchTag,$tableName) {
        $stmt = $this->_db->db->prepare(
            "INSERT INTO {$tableName} (id_vacancies,text_vacancies,date_add,search_tag)
                VALUES(:id_vacancies,:text_vacancies,:date_add,:search_tag)");
        $stmt->bindParam(':id_vacancies', $idVacancies);
        $stmt->bindParam(':text_vacancies', $text);
        $stmt->bindParam(':date_add', $dateAdd);
        $stmt->bindParam(':search_tag', $searchTag);
        $stmt->execute();
    }

    function getVacancyInfoByDateWithCityLike($from,$by,$tableName,$city,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag){
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancy},{$tableFieldTextVacancy}
            FROM {$tableName}
            WHERE dou_vacancy_info.id_vacancies IN (
                SELECT dou_cities.id_vacancy
                FROM dou_cities
                WHERE city RLIKE :city
            )
            AND date_add BETWEEN :from AND :by
            AND search_tag = :searchTag
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':searchTag', $searchTag);

        $stmt->execute();
        return $this->db2Arr($stmt);
    }

    function getVacancyInfoByDateWithCity($from,$by,$tableName,$city,$tableFieldIdVacancy,$tableFieldTextVacancy,$searchTag){
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancy},{$tableFieldTextVacancy}
            FROM {$tableName}
            WHERE dou_vacancy_info.id_vacancies IN (
                SELECT dou_cities.id_vacancy
                FROM dou_cities
                WHERE city = :city
            )
            AND date_add BETWEEN :from AND :by
            AND search_tag = :searchTag
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':searchTag', $searchTag);
        $stmt->execute();
        return $this->db2Arr($stmt);
    }
}

//$c = WorkWithDb5::getInstance();
//$x = $c->getVacancyInfoByDateWithCity('2015.05.03','2015.07.09','dou_vacancy_info','удаленная работа');
//echo '<pre>';
//print_r($x);
////var_dump($x[0]);
//
//foreach($x as $vacancyInfo){
//    $vacancyMap[$vacancyInfo['id_vacancies']] = $vacancyInfo['text_vacancies'];
//}
//var_dump($vacancyMap);
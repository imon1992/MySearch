<?php
//namespace BD\WorkWithDB;
include_once 'ConnectToDB.class.php';

class WorkWithDB2 {

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

    function giveData($arrayOfId) {
        $sql = "SELECT *
                        FROM rabota_vacancy_info
                        WHERE id_vacancies IN(" . implode(",", $arrayOfId) . ")";
        $queryResult = $this->_db->db->query($sql);

        return $this->db2Arr($queryResult);
    }
    
    function insertDataWithNowDate($idVacancies, $text,$companyId) {
        $stmt = $this->_db->db->prepare(
            "INSERT INTO rabota_vacancy_info (id_vacancies,text_vacancies,id_company,date_add)
                VALUES(:id_vacancies,:text_vacancies,:id_company,NOW())");
        $stmt->bindParam(':id_vacancies', $idVacancies);
        $stmt->bindParam(':text_vacancies', $text);
        $stmt->bindParam(':id_company', $companyId);
        $stmt->execute();
    }

    function insertDataWithDate($idVacancies, $text,$companyId,$timeInterval,$daysOrWeeks) {
            var_dump($daysOrWeeks);
        var_dump($timeInterval);
        $stmt = $this->_db->db->prepare(
            "INSERT INTO rabota_vacancy_info (id_vacancies,text_vacancies,id_company,date_add)
                VALUES(:id_vacancies,:text_vacancies,:id_company,DATE_ADD(NOW(), INTERVAL -".":daysOrWeeks"." $timeInterval))");
        $stmt->bindParam(':id_vacancies', $idVacancies);
        $stmt->bindParam(':text_vacancies', $text);
        $stmt->bindParam(':id_company', $companyId);
        $stmt->bindParam(':daysOrWeeks', $daysOrWeeks);
//        $stmt->bindParam(':timeInterval', $timeInterval);
        $stmt->execute();
    }

//    function insertDatenow(){
//        $stmt = $this->_db->db->prepare(
//            "INSERT INTO rabota_vacancy_info (id_vacancies,text_vacancies,id_company)
//                VALUES(:id_vacancies,:text_vacancies,:id_company)");
//        $stmt->execute();
//    }

}

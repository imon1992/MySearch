<?php

include_once 'ConnectToDB.class.php';

class WorkWithDB {

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
                        FROM dou_vacancy_info
                        WHERE id_vacancies IN(" . implode(",", $arrayOfId) . ")";
        $queryResult = $this->_db->db->query($sql);

        return $this->db2Arr($queryResult);
    }

    function getTowns($tag) {
        $stmt = $this->_db->db->prepare("SELECT town
                        FROM sectionAndTown
                        WHERE tag = :tag ");
    $stmt->bindParam(':tag', $tag);
        $stmt->execute();

        return $this->db2Arr($stmt);
    }

    function insertData($idVacancies, $text) {
        $stmt = $this->_db->db->prepare(
                "INSERT INTO dou_vacancy_info (id_vacancies,text_vacancies)
                VALUES(:id_vacancies,:text_vacancies)");
        $stmt->bindParam(':id_vacancies', $idVacancies);
        $stmt->bindParam(':text_vacancies', $text);
        $stmt->execute();
    }
    function insertSectionAndTown($town, $tag) {
        $stmt = $this->_db->db->prepare(
                "INSERT INTO sectionAndTown (tag,town)
                VALUES(:tag,:town)");
        $stmt->bindParam(':tag', $tag);
        $stmt->bindParam(':town', $town);
        $stmt->execute();
    }

}

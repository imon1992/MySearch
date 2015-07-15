<?php
include_once 'ConnectToDB.php';

class WorkWithDb
{

    protected $_db;
    protected static $_instance;

    private function __construct()
    {
        $db = new ConnectToDB();
        $db->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->_db = $db;
    }

    public function __destruct()
    {
        unset($this->_db);
    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        // проверяем актуальность экземпляра
        if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance;
    }

    protected function db2Arr($data)
    {
        $arr = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $arr[] = $row;
        return $arr;
    }

    function getVacancyInfoByDate($from, $by, $tableNameVacancyInfo, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo,
                                  $tableFieldTextVacancyVacancyInfo, $searchTag, $tableNameTags, $tableFieldIdVacancyTags,
                                  $tableFieldTagTags)
    {
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancyVacancyInfo},{$tableFieldTextVacancyVacancyInfo}
             FROM {$tableNameVacancyInfo}
             JOIN {$tableNameTags} on {$tableFieldIdVacancyVacancyInfo}={$tableFieldIdVacancyTags}
             WHERE {$tableFieldTagTags} = :searchTag
             AND {$tableFieldDateAddVacancyInfo} BETWEEN :from AND :by
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':searchTag', $searchTag);
        $stmt->execute();
        return $this->db2Arr($stmt);
    }

    function insertCities($vacancyId, $city, $tableName)
    {
        $stmt = $this->_db->db->prepare(
            "INSERT INTO {$tableName} (id_vacancy,city)
                VALUES(:id_vacancy,:city)");
        $stmt->bindParam(':id_vacancy', $vacancyId);
        $stmt->bindParam(':city', $city);
        $stmt->execute();
    }

    function insertTags($vacancyId, $tag, $tableName)
    {
        $stmt = $this->_db->db->prepare(
            "INSERT INTO {$tableName} (id_vacancy,tag)
                VALUES(:id_vacancy,:tag)");
        $stmt->bindParam(':id_vacancy', $vacancyId);
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();
    }

    function insertVacancyInfo($idVacancies, $text, $dateAdd, $tableName)
    {
        $stmt = $this->_db->db->prepare(
            "INSERT INTO {$tableName} (id_vacancies,text_vacancies,date_add)
                VALUES(:id_vacancies,:text_vacancies,:date_add)");
        $stmt->bindParam(':id_vacancies', $idVacancies);
        $stmt->bindParam(':text_vacancies', $text);
        $stmt->bindParam(':date_add', $dateAdd);
        $stmt->execute();
    }

    function getVacancyInfoByDateWithCityLike($from, $by, $tableNameVacancyInfo, $city, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo,
                                              $tableFieldTextVacancyVacancyInfo, $searchTag, $tableNameCities, $tableFieldIdVacancyCities,
                                              $tableFieldCityCities, $tableNameTags, $tableFieldIdVacancyTags, $tableFieldTagTags)
    {
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancyVacancyInfo},{$tableFieldTextVacancyVacancyInfo}
             FROM {$tableNameVacancyInfo}
             JOIN {$tableNameCities} on {$tableFieldIdVacancyVacancyInfo}={$tableFieldIdVacancyCities}
             JOIN {$tableNameTags} on {$tableFieldIdVacancyVacancyInfo}={$tableFieldIdVacancyTags}
             WHERE {$tableFieldTagTags} = :searchTag
             AND {$tableFieldCityCities} RLIKE :city
             AND {$tableFieldDateAddVacancyInfo} BETWEEN :from AND :by
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':searchTag', $searchTag);

        $stmt->execute();
        return $this->db2Arr($stmt);
    }

    function getVacancyInfoByDateWithCity($from, $by, $tableNameVacancyInfo, $city, $tableFieldIdVacancyVacancyInfo, $tableFieldDateAddVacancyInfo,
                                          $tableFieldTextVacancyVacancyInfo, $searchTag, $tableNameCities, $tableFieldIdVacancyCities,
                                          $tableFieldCityCities, $tableNameTags, $tableFieldIdVacancyTags, $tableFieldTagTags)
    {
        $stmt = $this->_db->db->prepare(
            "SELECT {$tableFieldIdVacancyVacancyInfo},{$tableFieldTextVacancyVacancyInfo}
             FROM {$tableNameVacancyInfo}
             JOIN {$tableNameCities} on {$tableFieldIdVacancyVacancyInfo}={$tableFieldIdVacancyCities}
             JOIN {$tableNameTags} on {$tableFieldIdVacancyVacancyInfo}={$tableFieldIdVacancyTags}
             WHERE {$tableFieldTagTags} = :searchTag
             AND {$tableFieldCityCities} = :city
             AND {$tableFieldDateAddVacancyInfo} BETWEEN :from AND :by
                ");
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':by', $by);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':searchTag', $searchTag);
        $stmt->execute();
        return $this->db2Arr($stmt);
    }

    function getVacancyIdAndText($arrayOfId, $tableName, $idVacancyField, $textVacancyField)
    {
        $sql = "SELECT {$idVacancyField},{$textVacancyField}
                        FROM {$tableName}
                        WHERE id_vacancies IN(" . implode(",", $arrayOfId) . ")";
        $queryResult = $this->_db->db->query($sql);

        return $this->db2Arr($queryResult);
    }

    function getCities($tableNameCities, $tableNameTags, $tableFieldVacancyIdCities, $tableFieldCityCities, $tableFieldIdVacancyTags, $tag)
    {
        $stmt = $this->_db->db->prepare(
            "SELECT DISTINCT {$tableFieldCityCities}
             FROM {$tableNameCities}
             JOIN {$tableNameTags} on {$tableFieldVacancyIdCities} = {$tableFieldIdVacancyTags}
             WHERE tag = :tag
                ");
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();
        return $this->db2Arr($stmt);
    }
}
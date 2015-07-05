<?php
class ConnectToDB {

    public $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=localhost; dbname=search', 'root','');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function __clone() {
        
    }

    function __destruct() {
        
    }

}
?>

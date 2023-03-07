<?php
require_once '../migrations-db.php';
class Basededonnee
{

    public $hostname, $dbname, $username, $password, $conn;

    public function __construct()
    {
        $bd_config = require '../migrations-db.php';
        $this->host_name = $bd_config["host"];
        $this->dbname = $bd_config["dbname"];
        $this->username = $bd_config["user"];
        $this->password = $bd_config["password"];
    }

    public function connexion()
    {
        $this->conn = null;
        try {

            $this->conn = new PDO("mysql:host=$this->host_name;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}

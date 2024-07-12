<?php

class Convertor{
    public $username;
    public $password;
    public $db;
    public $host;
    public $pdo;
    public $usd;

    public function __construct($username,$password,$db,$host){
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        $this->host = $host;
    }

    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function fetchAllRows() {
        try {
            $query = "SELECT * FROM convertor";
            $stmt = $this->pdo->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function insertData($a, $b, $c){
        echo "method working";
    try {
        $query = "INSERT INTO convertor (UserId, convertation, amount) VALUES (:a, :b, :c)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':a', $a);
        $stmt->bindParam(':b', $b);
        $stmt->bindParam(':c', $c);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
}

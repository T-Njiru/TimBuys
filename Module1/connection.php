<?php
class Database {
    private $host = 'localhost';  // Your database host
    private $dbname = 'timbuys';  // Your database name
    private $username = 'root';   // Your database username
    private $password = '';       // Your database password
   private $port = '3307';     //MY PORT
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>

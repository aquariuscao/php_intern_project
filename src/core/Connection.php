<?php


class Connection{

    protected $pdo;

    public function __construct()
    {
        $host = 'mysql'; // Tên dịch vụ MySQL trong Docker Compose
        $db = 'mvc_php';
        $user = 'mysql';
        $pass = 'mysql';

        try {
            // Tạo kết nối PDO
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}

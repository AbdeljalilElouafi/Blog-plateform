<?php

use Dotenv\Dotenv;

require '../vendor1/autoload.php';

class DatabaseConnection
{
    private $pdo;

    public function __construct()
    {
    
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        
        $this->connect();
    }

    private function connect()
    {
        try {
        
            $dsn = 'mysql:host=' . $_ENV['HOST'] . ';dbname=' . $_ENV['DATABASE'] . ';charset=utf8';
            $username = $_ENV['USERNAME'];
            $password = $_ENV['PASSWORD'];
            
        
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
        
            error_log("Connection failed: " . $e->getMessage());
            die("Connection failed. Please try again later.");
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}

?>
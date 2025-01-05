<?php

use Dotenv\Dotenv;

require '../vendor1/autoload.php';

class DatabaseConnection
{
    private $pdo;
    private static $instance = null;  // this private property is used to store the unique instance
    
    
    private function __construct()
    {
        // les variables d'environement dans .env
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        
        $this->connect();
    }

    // private method to prevent cloning the instance
    private function __clone() {}

    // private method to prevent another initialisation of the instance
    private function __wakeup() {}

    // method to get the unique instance
    public static function getInstance()
    {
        // condition to check if there is an instance already, if not it's created
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    // a method to connect to the database
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
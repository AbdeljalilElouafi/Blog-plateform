<?php
use Dotenv\Dotenv;

require '../vendor1/autoload.php'; // Composer autoloader 
// Make sure to give the correct path to the autoloader file.

// Load .env file from the root of your project
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 * Connect to a MySQL database using PDO.
 *
 * This function establishes a connection to a MySQL database. If the
 * connection fails, it logs an error and terminates the program.
 *
 * @return PDO A PDO connection object.
 */
function connect_db() {
    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . $_ENV['HOST'] . ';dbname=' . $_ENV['DATABASE'] . ';charset=utf8';
        $username = $_ENV['USERNAME'];
        $password = $_ENV['PASSWORD'];
        
        // Create a PDO instance and set error mode to exception
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        return $pdo;
    } catch (PDOException $e) {
        // Log the error and terminate if the connection fails
        error_log("Connection failed: " . $e->getMessage());
        die("Connection failed. Please try again later.");
    }
}

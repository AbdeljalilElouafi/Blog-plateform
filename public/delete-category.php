<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Category.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();


if (isset($_GET['Category_id'])) {
    $Category_id = $_GET['Category_id'];

    
    $Category = new Category($pdo);

    
    $Category->removeCategory($Category_id);
    echo "Category deleted successfully!";
} else {
    echo "Category ID is required.";
    exit;
}
?>

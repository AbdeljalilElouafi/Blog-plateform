<?php
require_once '../../config/database.php';
require_once '../Model/Crud.php';
require_once '../Model/User.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
session_start();
$user = new User($pdo);

$user->logout();

?>
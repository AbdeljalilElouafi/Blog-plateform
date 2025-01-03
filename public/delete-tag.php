<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Tag.php';

$db = new DatabaseConnection();
$pdo = $db->getPdo();


if (isset($_GET['tag_id'])) {
    $tag_id = $_GET['tag_id'];

    
    $tag = new Tag($pdo);

    
    $tag->removeTag($tag_id);
    echo "Tag deleted successfully!";
} else {
    echo "Tag ID is required.";
    exit;
}
?>

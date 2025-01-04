<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Article.php';

$db = new DatabaseConnection();
$pdo = $db->getPdo();

if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];
    
    $article = new Article($pdo);
    
    $article->removeArticle($article_id);
    echo "Article deleted successfully!";
} else {
    echo "Article ID is required.";
    exit;
}
?>
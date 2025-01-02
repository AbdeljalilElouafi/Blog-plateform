<?php

class Category {
    public $id;
    public $name;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function displayCategories() {
        try {    
            $stmt = $this->pdo->query("SELECT * FROM categories");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "Category: " . " " . $row["name"] . "<br>";
                }
            } else {
                echo "No categories found";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}


?>

<!-- // this is how im going to use this class: -->

<!-- $category = new Category($pdo);
$category->displayCategories(); -->



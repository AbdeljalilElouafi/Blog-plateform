<?php

require_once 'Crud.php';


class Category extends Crud {
    public $id;
    public $name;

    public function displayCategories() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function addCategory($name) {
        $data = ['name' => $name];
        $this->insertRecord('categories', $data);
    }

    public function editCategory($id, $name) {
        $data = ['name' => $name];
        $this->updateRecord('categories', $data, $id);
    }

    public function removeCategory($id) {
        $this->deleteRecord('categories', $id);
    }

    public function countCategories() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM categories");
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }


}
?>


<!-- // this is how im going to use this class: -->

<!-- $category = new Category($pdo);
$category->displayCategories(); -->



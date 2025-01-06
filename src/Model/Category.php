<?php

require_once 'Crud.php';


class Category extends Crud {
    public $id;
    public $name;

    public function displayCategories() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='flex items-center gap-2'>";
                    echo "<span>Category: " . $row["name"] . "</span>";
                    echo "<div class='mt-3'>";
                    echo "<a href='edit-category.php?Category_id=" . $row['id'] . "' class='btn btn-success btn-sm me-2'>Edit</a>";
                    echo "<a href='delete-category.php?Category_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No categories found";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
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
}
?>


<!-- // this is how im going to use this class: -->

<!-- $category = new Category($pdo);
$category->displayCategories(); -->



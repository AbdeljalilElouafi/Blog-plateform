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

    public function insertRecord($table, $data) {
        try {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));

            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $stmt = $this->pdo->prepare($sql);

            $types = str_repeat('s', count($data));
            $params = array_values($data);
            $stmt->execute($params);

            echo "Record added successfully!";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateRecord($table, $data, $id) {
        try {
            $args = array();

            foreach ($data as $key => $value) {
                $args[] = "$key = ?";
            }

            $sql = "UPDATE $table SET " . implode(',', $args) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);

            $types = str_repeat('s', count($data)) . 'i';
            $params = array_values($data);
            $params[] = $id;
            $stmt->execute($params);

            echo "Record updated successfully!";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteRecord($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            echo "Record deleted successfully!";
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



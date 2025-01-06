<?php

require_once 'Crud.php';

class Tag extends Crud {
    public $id;
    public $name;

    public function displayTags() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM tags");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='flex items-center gap-2'>";
                    echo "<span>Tag: " . $row["name"] . "</span>";
                    echo "<div class='mt-3'>";
                    echo "<a href='edit-tag.php?tag_id=" . $row['id'] . "' class='btn btn-success btn-sm me-2'>Edit</a>";
                    echo "<a href='delete-tag.php?tag_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No tags found";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addTag($name) {
        $data = ['name' => $name];
        $this->insertRecord('tags', $data);
    }

    public function editTag($id, $name) {
        $data = ['name' => $name];
        $this->updateRecord('tags', $data, $id);
    }

    public function removeTag($id) {
        $this->deleteRecord('tags', $id);
    }
}


?>

<!-- // this is how im going to use this class: -->


<!-- $tag = new Tag($pdo);
$tag->displayTags(); -->
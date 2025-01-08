<?php

require_once 'Crud.php';

class Tag extends Crud {
    public $id;
    public $name;

    public function displayTags() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM tags");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
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
  

    public function countTags() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM tags");
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

}
  

?>

<!-- // this is how im going to use this class: -->


<!-- $tag = new Tag($pdo);
$tag->displayTags(); -->
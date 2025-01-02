<?php

class Tag {
    public $id;
    public $name;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function displayTags() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM tags");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "Tag: " . " " . $row["name"] . "<br>";
                }
            } else {
                echo "No tags found";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}


?>

<!-- // this is how im going to use this class: -->


<!-- $tag = new Tag($pdo);
$tag->displayTags(); -->
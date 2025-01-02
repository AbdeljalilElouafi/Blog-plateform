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

                    echo "<a href='edit-tag.php?tag_id=" . $row['id'] . "' class='mt-10 flex w-10 items-center justify-center rounded-md border border-transparent bg-green-600 px-8 py-3 text-base font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2'>Edit</a>";

                    echo "<a href='delete-tag.php?tag_id=" . $row['id'] . "' class='mt-10 flex w-10 items-center justify-center rounded-md border border-transparent bg-red-600 px-8 py-3 text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'>Delete</a>";



                }
            } else {
                echo "No tags found";
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
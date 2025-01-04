<?php

class Article {
    public $id;
    public $title;
    public $slug;
    public $content;
    public $excerpt;
    public $meta_description;
    public $category_id;
    public $featured_image;
    public $status;
    public $scheduled_date;
    public $author_id;
    public $created_at;
    public $updated_at;
    public $views;
    private $pdo;




    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function insertRecord($table, $data) {
        try {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));

            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $stmt = $this->pdo->prepare($sql);

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



    public function displayArticles() {
        try {
            $stmt = $this->pdo->query("SELECT a.*, c.name as category_name, u.username as author_name 
                                     FROM articles a 
                                     LEFT JOIN categories c ON a.category_id = c.id 
                                     LEFT JOIN users u ON a.author_id = u.id");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='flex items-center gap-2 mb-4'>";
                    echo "<div class='flex-grow'>";
                    echo "<h3 class='font-bold'>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<p class='text-sm'>Category: " . htmlspecialchars($row["category_name"]) . 
                         " | Author: " . htmlspecialchars($row["author_name"]) . 
                         " | Status: " . htmlspecialchars($row["status"]) . "</p>";
                    echo "</div>";
                    echo "<a href='edit-article.php?article_id=" . $row['id'] . "' class='flex items-center justify-center rounded-md border border-transparent bg-green-600 px-1 py-1 text-base font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2'>Edit</a>";
                    echo "<a href='delete-article.php?article_id=" . $row['id'] . "' class='flex items-center justify-center rounded-md border border-transparent bg-red-600 px-1 py-1 text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'>Delete</a>";
                    echo "</div>";
                    
                    
                    // $this->displayArticleTags($row['id']);
                }
            } else {
                echo "No articles found";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function addArticle($data) {
        
        // if (!isset($data['slug'])) {
        //     $data['slug'] = $this->generateSlug($data['title']);
        // }
        
        
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }
        
        $this->insertRecord('articles', $data);
    }
    

    public function editArticle($id, $data) {
        
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        $this->updateRecord('articles', $data, $id);
    }
    


    public function removeArticle($id) {
        $this->deleteRecord('articles', $id);
    }


}
?>
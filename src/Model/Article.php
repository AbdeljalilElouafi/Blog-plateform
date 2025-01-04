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
            $stmt = $this->pdo->query("SELECT articles.*, categories.name as category_name, users.username as author_name 
                                     FROM articles  
                                     LEFT JOIN categories ON articles.category_id = categories.id 
                                     LEFT JOIN users ON articles.author_id = users.id");
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
        
        if (!isset($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        
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

    public function changeStatus($articleId, $newStatus, $scheduledDate = null) {
        try {
            $data = ['status' => $newStatus];
            if ($newStatus === 'scheduled') {
                if (!$scheduledDate) {
                    throw new Exception("Scheduled date is required for scheduled status");
                }
                $data['scheduled_date'] = $scheduledDate;
            }
            $this->updateRecord('articles', $data, $articleId);
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    
    public function setCategory($articleId, $categoryId) {
        $data = ['category_id' => $categoryId];
        $this->updateRecord('articles', $data, $articleId);
    }
    
    
    public function addTag($articleId, $tagId) {
        $data = [
            'article_id' => $articleId,
            'tag_id' => $tagId
        ];
        $this->insertRecord('article_tags', $data);
    }
    
    public function removeTag($articleId, $tagId) {
        try {
            $sql = "DELETE FROM article_tags WHERE article_id = ? AND tag_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$articleId, $tagId]);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    
    private function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $baseSlug = $slug;
        $counter = 1;
        
        // while ($this->slugExists($slug)) {
        //     $slug = $baseSlug . '-' . $counter;
        //     $counter++;
        // }
        
        return $slug;
    }
    
    
    public function displayArticleTags($articleId) {
        try {
            $sql = "SELECT t.* FROM tags t 
                    JOIN article_tags at ON t.id = at.tag_id 
                    WHERE at.article_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$articleId]);
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($tags)) {
                echo "<div class='flex flex-wrap gap-2 mt-2'>";
                foreach ($tags as $tag) {
                    echo "<span class='px-2 py-1 bg-gray-200 rounded-full text-sm'>" . 
                         htmlspecialchars($tag['name']) . "</span>";
                }
                echo "</div>";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

}
?>
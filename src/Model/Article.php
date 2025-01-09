<?php

require_once 'Crud.php';


class Article extends Crud {
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

    public function displayArticles() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    a.*, 
                    c.name as category_name,
                    u.username,
                    GROUP_CONCAT(t.name) as tag_name
                FROM articles a
                LEFT JOIN categories c ON a.category_id = c.id 
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                GROUP BY a.id
            ");
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


    public function getArticleById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*, 
                    c.name as category_name,
                    u.username,
                    GROUP_CONCAT(t.name) as tag_name
                FROM articles a
                LEFT JOIN categories c ON a.category_id = c.id 
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                WHERE a.id = ?
                GROUP BY a.id
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
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
                echo "<div class='d-flex flex-wrap gap-2 mt-2'>";
                foreach ($tags as $tag) {
                    echo "<span class='badge bg-primary rounded-pill'>" .
                         htmlspecialchars($tag['name']) . "</span>";
                }
                echo "</div>";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getTopArticles($limit = 5) {
        try {
            $sql = "SELECT a.*, u.username 
                    FROM articles a 
                    LEFT JOIN users u ON a.author_id = u.id 
                    ORDER BY a.views DESC, a.created_at DESC 
                    LIMIT " . (int)$limit;  
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();  
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function countArticles($status = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM articles";
            if ($status) {
                $sql .= " WHERE status = :status";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['status' => $status]);
            } else {
                $stmt = $this->pdo->query($sql);
            }
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function incrementViews($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getArticlesByStatus($status) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*, 
                    c.name as category_name,
                    u.username,
                    GROUP_CONCAT(t.name) as tag_name
                FROM articles a
                LEFT JOIN categories c ON a.category_id = c.id 
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                WHERE a.status = :status
                GROUP BY a.id
            ");
            $stmt->execute(['status' => $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    

}
?>
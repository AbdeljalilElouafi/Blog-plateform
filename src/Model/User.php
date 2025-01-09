<?php
require_once 'Crud.php';


class User extends Crud {
    public $id;
    public $username;
    public $email;
    public $role;
    public $bvio;



    public function getUsers() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY id ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public function register($username, $email, $password) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash,
                'role' => 'user'
            ];
            return $this->insertRecord('users', $data);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function updateRole($userId, $newRole) {
        if ($_SESSION['role'] !== 'admin') {
            return false;
        }
    
        
        $validRoles = ['user', 'author', 'admin']; 
        if (!in_array($newRole, $validRoles)) {
            return false;
        }
    
        $data = ['role' => $newRole];
        return $this->updateRecord('users', $data, $userId);
    }

    public static function checkAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }
    }

    public static function requireRole($requiredRole) {
        self::checkAuth();
        if ($_SESSION['role'] !== $requiredRole && $_SESSION['role'] !== 'admin') {
            header('Location: unauthorized.php');
            exit();
        }
    }

    public function logout() {
        // session_start();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

    public function countUsers() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users");
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public function getCurrentUser() {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateProfile($userId, $data) {
        return $this->updateRecord('users', $data, $userId);
    }

    public function getAuthors() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, 
                       (SELECT COUNT(*) FROM articles WHERE author_id = u.id) as article_count
                FROM users u 
                WHERE u.role = 'author' OR u.role = 'admin'
                ORDER BY u.id DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }

}
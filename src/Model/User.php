<?php
require_once 'Crud.php';


class User extends Crud {
    public $id;
    public $username;
    public $email;
    public $role;
    public $bio;

    
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
        session_start();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
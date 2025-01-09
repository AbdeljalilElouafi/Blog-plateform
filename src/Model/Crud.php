<?php

abstract class Crud {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function insertRecord($table, $data) {
        try {
            $columns = implode(',', array_keys($data));
            $values = implode(',', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $stmt = $this->pdo->prepare($sql);
            $params = array_values($data);
            $stmt->execute($params);
            // echo "Record added successfully!";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    protected function updateRecord($table, $data, $id) {
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
            // echo "Record updated successfully!";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    protected function deleteRecord($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            // echo "Record deleted successfully!";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}


?>
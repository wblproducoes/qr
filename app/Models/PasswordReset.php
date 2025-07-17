<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PasswordReset
{
    private $conn;
    private $table = 'password_resets';

    public $id;
    public $user_id;
    public $token;
    public $expires_at;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO {$this->table} (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";
        $stmt = $this->conn->prepare($query);

        $this->token = htmlspecialchars(strip_tags($this->token));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':token', $this->token);
        $stmt->bindParam(':expires_at', $this->expires_at);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function findByToken($token)
    {
        $query = "SELECT * FROM {$this->table} WHERE token = :token AND expires_at > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}

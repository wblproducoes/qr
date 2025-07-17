<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class QrCode
{
    private $conn;
    private $table = 'qrcodes';

    public $id;
    public $user_id;
    public $ip_address;
    public $content;
    public $image_path;
    public $expires_at;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO {$this->table} (user_id, ip_address, content, image_path, expires_at) VALUES (:user_id, :ip_address, :content, :image_path, :expires_at)";
        $stmt = $this->conn->prepare($query);

        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':ip_address', $this->ip_address);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':image_path', $this->image_path);
        $stmt->bindParam(':expires_at', $this->expires_at);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function findByIp($ip)
    {
        $query = "SELECT * FROM {$this->table} WHERE ip_address = :ip AND expires_at > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserId($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}

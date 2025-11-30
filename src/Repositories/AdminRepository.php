<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

class AdminRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT admin_id AS id, username, password, nama 
                FROM admin 
                WHERE username = :username LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }
}

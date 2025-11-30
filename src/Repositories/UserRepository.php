<?php
namespace App\Repositories;

use App\Config\Database;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\Admin;
use App\Models\Pegawai;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        return [];
    }

    public function findById(int $id, string $type = 'pegawai')
    {
        $table = $type === 'admin' ? 'admin' : 'pegawai';
        $idColumn = $type === 'admin' ? 'admin_id' : 'pegawai_id';
        
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$idColumn} = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        if ($type === 'admin') {
            return (new Admin())->fromArray($data);
        } else {
            return (new Pegawai())->fromArray($data);
        }
    }

    public function findByUsername(string $username)
    {
        // Try admin first
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();
        
        if ($data) {
            return (new Admin())->fromArray($data);
        }
        
        // Try pegawai
        $stmt = $this->db->prepare("SELECT * FROM pegawai WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();
        
        if ($data) {
            return (new Pegawai())->fromArray($data);
        }
        
        return null;
    }

    public function create(array $data)
    {
        $type = $data['type'] ?? 'pegawai';
        $table = $type === 'admin' ? 'admin' : 'pegawai';
        
        $sql = "INSERT INTO {$table} (username, password) VALUES (:username, :password)";
        $stmt = $this->db->prepare($sql);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt->execute([
            'username' => $data['username'],
            'password' => $hashedPassword
        ]);
        
        $id = (int)$this->db->lastInsertId();
        return $this->findById($id, $type);
    }

    public function update(int $id, array $data): bool
    {
        return true;
    }

    public function delete(int $id): bool
    {
        return true;
    }
}
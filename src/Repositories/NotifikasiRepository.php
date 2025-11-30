<?php
namespace App\Repositories;

use App\Config\Database;
use App\Repositories\Interfaces\NotifikasiRepositoryInterface;
use App\Models\Notifikasi;
use PDO;

class NotifikasiRepository implements NotifikasiRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM notifikasi ORDER BY created_at DESC");
        $results = [];
        
        while ($row = $stmt->fetch()) {
            $results[] = (new Notifikasi())->fromArray($row);
        }
        
        return $results;
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM notifikasi WHERE notifikasi_id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        return (new Notifikasi())->fromArray($data);
    }

    public function findByPegawaiId(int $pegawaiId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifikasi WHERE pegawai_id = :pegawai_id ORDER BY created_at DESC"
        );
        $stmt->execute(['pegawai_id' => $pegawaiId]);
        
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = (new Notifikasi())->fromArray($row);
        }
        
        return $results;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO notifikasi (pegawai_id, rapat_id, pesan) 
                VALUES (:pegawai_id, :rapat_id, :pesan)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'pegawai_id' => $data['pegawai_id'],
            'rapat_id' => $data['rapat_id'] ?? null,
            'pesan' => $data['pesan']
        ]);
        
        $id = (int)$this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(int $id, array $data): bool
    {
        return false;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM notifikasi WHERE notifikasi_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
<?php    
namespace App\Repositories;

use App\Config\Database;
use App\Repositories\Interfaces\RapatRepositoryInterface;
use App\Models\Rapat;
use PDO;

class RapatRepository implements RapatRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM rapat ORDER BY tanggal_mulai DESC");
        $results = [];
        
        while ($row = $stmt->fetch()) {
            $results[] = (new Rapat())->fromArray($row);
        }
        
        return $results;
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM rapat WHERE rapat_id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        return (new Rapat())->fromArray($data);
    }

    public function findByDate(string $date): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM rapat WHERE DATE(tanggal_mulai) = :date ORDER BY tanggal_mulai"
        );
        $stmt->execute(['date' => $date]);
        
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = (new Rapat())->fromArray($row);
        }
        
        return $results;
    }

    public function findByDateRange(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM rapat 
             WHERE tanggal_mulai >= :start AND tanggal_mulai <= :end 
             ORDER BY tanggal_mulai"
        );
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = (new Rapat())->fromArray($row);
        }
        
        return $results;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO rapat (judul, deskripsi, lokasi, tanggal_mulai, tanggal_selesai, dibuat_oleh) 
                VALUES (:judul, :deskripsi, :lokasi, :tanggal_mulai, :tanggal_selesai, :dibuat_oleh)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'lokasi' => $data['lokasi'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'dibuat_oleh' => $data['dibuat_oleh']
        ]);
        
        $id = (int)$this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE rapat SET 
                judul = :judul,
                deskripsi = :deskripsi,
                lokasi = :lokasi,
                tanggal_mulai = :tanggal_mulai,
                tanggal_selesai = :tanggal_selesai,
                diubah_oleh = :diubah_oleh
                WHERE rapat_id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'lokasi' => $data['lokasi'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'diubah_oleh' => $data['diubah_oleh']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM rapat WHERE rapat_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function findWithPagination(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare(
            "SELECT * FROM rapat ORDER BY tanggal_mulai DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = (new Rapat())->fromArray($row);
        }
        
        $countStmt = $this->db->query("SELECT COUNT(*) as total FROM rapat");
        $total = (int)$countStmt->fetch()['total'];
        
        return [
            'data' => $results,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }
}
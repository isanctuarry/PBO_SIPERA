<?php
namespace App\Repositories;

use App\Models\Rapat;
use PDO;

class RapatRepository extends BaseRepository
{
    protected string $table = 'rapat';

    public function all(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY tanggal_mulai DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE rapat_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(array $payload): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (judul, deskripsi, lokasi, tanggal_mulai, tanggal_selesai, dibuat_oleh) VALUES (:judul, :deskripsi, :lokasi, :tanggal_mulai, :tanggal_selesai, :dibuat_oleh)");
        $stmt->execute([
            ':judul' => $payload['judul'],
            ':deskripsi' => $payload['deskripsi'] ?? null,
            ':lokasi' => $payload['lokasi'] ?? null,
            ':tanggal_mulai' => $payload['tanggal_mulai'],
            ':tanggal_selesai' => $payload['tanggal_selesai'],
            ':dibuat_oleh' => $payload['dibuat_oleh']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $payload): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET judul=:judul, deskripsi=:deskripsi, lokasi=:lokasi, tanggal_mulai=:tanggal_mulai, tanggal_selesai=:tanggal_selesai, diubah_oleh=:diubah_oleh WHERE rapat_id=:id");
        return $stmt->execute([
            ':judul' => $payload['judul'],
            ':deskripsi' => $payload['deskripsi'] ?? null,
            ':lokasi' => $payload['lokasi'] ?? null,
            ':tanggal_mulai' => $payload['tanggal_mulai'],
            ':tanggal_selesai' => $payload['tanggal_selesai'],
            ':diubah_oleh' => $payload['diubah_oleh'] ?? null,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE rapat_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

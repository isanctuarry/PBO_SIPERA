<?php
namespace App\Services;

use App\Repositories\NotifikasiRepository;
use App\Factories\NotifikasiFactory;
use App\Config\Database;
use PDO;

class NotifikasiService
{
    private NotifikasiRepository $notifikasiRepository;
    private PDO $db;

    public function __construct(NotifikasiRepository $notifikasiRepository)
    {
        $this->notifikasiRepository = $notifikasiRepository;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getNotifikasiForPegawai(int $pegawaiId, ?int $page = null, ?int $limit = null): array
    {
        $notifikasi = $this->notifikasiRepository->findByPegawaiId($pegawaiId);

        if ($page && $limit) {
            $offset = ($page - 1) * $limit;
            $total = count($notifikasi);
            $notifikasi = array_slice($notifikasi, $offset, $limit);

            return [
                'data' => $notifikasi,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ];
        }

        return ['data' => $notifikasi];
    }

    public function notifyAllPegawai(int $rapatId, string $message): void
    {
        $stmt = $this->db->query("SELECT pegawai_id FROM pegawai");
        $pegawaiList = $stmt->fetchAll();

        foreach ($pegawaiList as $pegawai) {
            $notifikasi = NotifikasiFactory::create(
                $pegawai['pegawai_id'],
                $rapatId,
                $message
            );

            $this->notifikasiRepository->create($notifikasi->toArray());
        }
    }

    public function notifyPegawai(int $pegawaiId, int $rapatId, string $message): void
    {
        $notifikasi = NotifikasiFactory::create($pegawaiId, $rapatId, $message);
        $this->notifikasiRepository->create($notifikasi->toArray());
    }

    public function deleteNotifikasi(int $id): bool
    {
        return $this->notifikasiRepository->delete($id);
    }
}
<?php
namespace App\Services;

use App\Repositories\RapatRepository;
use App\Repositories\NotifikasiRepository;
use App\Models\Rapat;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Config\Database;

class RapatService
{
    private RapatRepository $rapatRepository;
    private NotifikasiRepository $notifikasiRepository;
    private NotifikasiService $notifikasiService;

    public function __construct(
        RapatRepository $rapatRepository,
        NotifikasiRepository $notifikasiRepository,
        NotifikasiService $notifikasiService
    ) {
        $this->rapatRepository = $rapatRepository;
        $this->notifikasiRepository = $notifikasiRepository;
        $this->notifikasiService = $notifikasiService;
    }

    public function getAllRapat(?string $date = null, ?int $page = null, ?int $limit = null): array
    {
        if ($date) {
            return ['data' => $this->rapatRepository->findByDate($date)];
        }

        if ($page && $limit) {
            return $this->rapatRepository->findWithPagination($page, $limit);
        }

        return ['data' => $this->rapatRepository->findAll()];
    }

    public function getRapatById(int $id): Rapat
    {
        $rapat = $this->rapatRepository->findById($id);
        
        if (!$rapat) {
            throw new NotFoundException("Rapat with ID {$id} not found");
        }

        return $rapat;
    }

    public function createRapat(array $data, int $adminId): Rapat
    {
        $rapat = new Rapat();
        $rapat->fromArray($data);
        $rapat->setDibuatOleh($adminId);

        $errors = $rapat->validate();
        if (!empty($errors)) {
            throw new ValidationException('Validation failed', $errors);
        }

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            $createdRapat = $this->rapatRepository->create($rapat->toArray());

            $this->notifikasiService->notifyAllPegawai(
                $createdRapat->getRapatId(),
                "Rapat baru: " . $createdRapat->getJudul()
            );

            $db->commit();
            return $createdRapat;
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function updateRapat(int $id, array $data, int $adminId): bool
    {
        $existingRapat = $this->getRapatById($id);

        $rapat = new Rapat();
        $rapat->fromArray($data);
        $rapat->setDiubahOleh($adminId);

        $errors = $rapat->validate();
        if (!empty($errors)) {
            throw new ValidationException('Validation failed', $errors);
        }

        $dataToUpdate = $rapat->toArray();
        $dataToUpdate['diubah_oleh'] = $adminId;
        
        $updated = $this->rapatRepository->update($id, $dataToUpdate);

        if ($updated) {
            $this->notifikasiService->notifyAllPegawai(
                $id,
                "Rapat diubah: " . $existingRapat->getJudul()
            );
        }

        return $updated;
    }

    public function deleteRapat(int $id): bool
    {
        $rapat = $this->getRapatById($id);
        
        $this->notifikasiService->notifyAllPegawai(
            $id,
            "Rapat dibatalkan: " . $rapat->getJudul()
        );

        return $this->rapatRepository->delete($id);
    }
}
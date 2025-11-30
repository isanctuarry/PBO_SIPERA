<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\NotifikasiService;
use App\Utils\ApiResponse;

class NotifikasiController extends Controller
{
    private NotifikasiService $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function kirim(): void
    {
        try {
            $data = $this->getJsonInput();

            $this->notifikasiService->notifyPegawai(
                $data['pegawai_id'],
                $data['rapat_id'] ?? null,
                $data['pesan']
            );

            ApiResponse::created(null, 'Notifikasi sent successfully')->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->notifikasiService->deleteNotifikasi($id);
            ApiResponse::ok(null, 'Notifikasi deleted successfully')->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }
}
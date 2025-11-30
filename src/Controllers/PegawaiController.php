<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\RapatService;
use App\Services\NotifikasiService;
use App\Utils\ApiResponse;

class PegawaiController extends Controller
{
    private RapatService $rapatService;
    private NotifikasiService $notifikasiService;

    public function __construct(
        RapatService $rapatService,
        NotifikasiService $notifikasiService
    ) {
        $this->rapatService = $rapatService;
        $this->notifikasiService = $notifikasiService;
    }

    public function getJadwal(): void
    {
        try {
            $params = $this->getQueryParams();
            $tanggal = $params['tanggal'] ?? null;

            $result = $this->rapatService->getAllRapat($tanggal);

            ApiResponse::ok($result['data'], 'Jadwal retrieved successfully')->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function getNotifikasi(array $user): void
    {
        try {
            $params = $this->getQueryParams();
            $page = isset($params['page']) ? (int)$params['page'] : null;
            $limit = isset($params['limit']) ? (int)$params['limit'] : null;

            $result = $this->notifikasiService->getNotifikasiForPegawai(
                $user['id'],
                $page,
                $limit
            );

            ApiResponse::ok($result['data'], 'Notifikasi retrieved successfully')
                ->meta($result['pagination'] ?? [])
                ->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }
}
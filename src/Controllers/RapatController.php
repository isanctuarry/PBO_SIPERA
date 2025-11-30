<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\RapatService;
use App\Utils\ApiResponse;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;

class RapatController extends Controller
{
    private RapatService $rapatService;

    public function __construct(RapatService $rapatService)
    {
        $this->rapatService = $rapatService;
    }

    public function index(): void
    {
        try {
            $params = $this->getQueryParams();
            
            $page = isset($params['page']) ? (int)$params['page'] : null;
            $limit = isset($params['limit']) ? (int)$params['limit'] : null;
            $tanggal = $params['tanggal'] ?? null;

            $result = $this->rapatService->getAllRapat($tanggal, $page, $limit);

            ApiResponse::ok($result['data'], 'Rapat retrieved successfully')
                ->meta($result['pagination'] ?? [])
                ->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function show(int $id): void
    {
        try {
            $rapat = $this->rapatService->getRapatById($id);
            ApiResponse::ok($rapat->toArray(), 'Rapat retrieved successfully')->send();
        } catch (NotFoundException $e) {
            ApiResponse::notFound($e->getMessage())->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function store(array $user): void
    {
        try {
            $data = $this->getJsonInput();
            
            $rapat = $this->rapatService->createRapat($data, $user['id']);
            
            ApiResponse::created($rapat->toArray(), 'Rapat created successfully')->send();
        } catch (ValidationException $e) {
            ApiResponse::badRequest($e->getMessage(), $e->getErrors())->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function update(int $id, array $user): void
    {
        try {
            $data = $this->getJsonInput();
            
            $this->rapatService->updateRapat($id, $data, $user['id']);
            
            ApiResponse::ok(null, 'Rapat updated successfully')->send();
        } catch (NotFoundException $e) {
            ApiResponse::notFound($e->getMessage())->send();
        } catch (ValidationException $e) {
            ApiResponse::badRequest($e->getMessage(), $e->getErrors())->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->rapatService->deleteRapat($id);
            ApiResponse::ok(null, 'Rapat deleted successfully')->send();
        } catch (NotFoundException $e) {
            ApiResponse::notFound($e->getMessage())->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }
}
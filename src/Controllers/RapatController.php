<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\RapatService;
use App\Builders\ApiResponseBuilder;

class RapatController
{
    private RapatService $service;

    public function __construct()
    {
        $this->service = new RapatService();
    }

    public function index(Request $request): Response
    {
        $page = isset($request->query['page']) ? (int)$request->query['page'] : 1;
        $limit = isset($request->query['limit']) ? (int)$request->query['limit'] : 10;
        $items = $this->service->list($page, $limit);

        $payload = ApiResponseBuilder::make()
            ->status('success')
            ->message('Daftar rapat')
            ->data(['items' => $items])
            ->build();

        return new Response($payload, 200);
    }

    public function show(Request $request, $id): Response
    {
        $item = $this->service->get((int)$id);

        $payload = ApiResponseBuilder::make()
            ->status('success')
            ->message('Detail rapat')
            ->data($item)
            ->build();

        return new Response($payload, 200);
    }

    public function store(Request $request): Response
    {
        // in real app, get user id from JWT
        $payload = $request->body;
        $payload['dibuat_oleh'] = $payload['dibuat_oleh'] ?? 1;

        $newId = $this->service->create($payload);

        $responsePayload = ApiResponseBuilder::make()
            ->status('success')
            ->message('Rapat dibuat')
            ->data(['rapat_id' => $newId])
            ->build();

        return new Response($responsePayload, 201);
    }

    public function update(Request $request, $id): Response
    {
        $payload = $request->body;
        $payload['diubah_oleh'] = $payload['diubah_oleh'] ?? 1;

        $this->service->update((int)$id, $payload);

        $responsePayload = ApiResponseBuilder::make()
            ->status('success')
            ->message('Rapat diperbarui')
            ->data(null)
            ->build();

        return new Response($responsePayload, 200);
    }

    public function delete(Request $request, $id): Response
    {
        $this->service->delete((int)$id);
        $payload = ApiResponseBuilder::make()
            ->status('success')
            ->message('Rapat dihapus')
            ->data(null)
            ->build();

        return new Response($payload, 200);
    }
}

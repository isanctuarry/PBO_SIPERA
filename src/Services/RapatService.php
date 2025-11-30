<?php
namespace App\Services;

use App\Repositories\RapatRepository;
use App\Models\Rapat;
use App\Exceptions\ValidationException;

class RapatService
{
    private RapatRepository $repo;

    public function __construct()
    {
        $this->repo = new RapatRepository();
    }

    public function list(int $page = 1, int $limit = 10): array
    {
        $offset = max(0, ($page - 1) * $limit);
        $items = $this->repo->all($limit, $offset);
        return $items;
    }

    public function get(int $id): array
    {
        $rapat = $this->repo->find($id);
        if (!$rapat) {
            throw new \App\Exceptions\HttpException(404, "Rapat dengan id {$id} tidak ditemukan");
        }
        return $rapat;
    }

    public function create(array $data): int
    {
        $this->validate($data);
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $this->validate($data, true);
        $exists = $this->repo->find($id);
        if (!$exists) throw new \App\Exceptions\HttpException(404, 'Rapat tidak ditemukan');
        return $this->repo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $exists = $this->repo->find($id);
        if (!$exists) throw new \App\Exceptions\HttpException(404, 'Rapat tidak ditemukan');
        return $this->repo->delete($id);
    }

    private function validate(array $data, bool $forUpdate = false): void
    {
        $errors = [];
        if (empty($data['judul'])) $errors['judul'] = 'Judul wajib diisi';
        if (empty($data['tanggal_mulai'])) $errors['tanggal_mulai'] = 'tanggal_mulai wajib diisi';
        if (empty($data['tanggal_selesai'])) $errors['tanggal_selesai'] = 'tanggal_selesai wajib diisi';
        // simple ISO datetime check
        if (!empty($data['tanggal_mulai']) && !strtotime($data['tanggal_mulai'])) $errors['tanggal_mulai'] = 'Format tanggal_mulai tidak valid';
        if (!empty($data['tanggal_selesai']) && !strtotime($data['tanggal_selesai'])) $errors['tanggal_selesai'] = 'Format tanggal_selesai tidak valid';
        if (!empty($errors)) throw new ValidationException($errors);
    }
}

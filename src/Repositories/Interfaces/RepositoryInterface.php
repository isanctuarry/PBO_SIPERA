<?php
// ========================================
// File: src/Repositories/Interfaces/RepositoryInterface.php
// ========================================

namespace App\Repositories\Interfaces;

interface RepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
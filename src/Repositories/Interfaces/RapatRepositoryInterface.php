<?php
// ========================================
// File: src/Repositories/Interfaces/RapatRepositoryInterface.php
// ========================================

namespace App\Repositories\Interfaces;

interface RapatRepositoryInterface extends RepositoryInterface
{
    public function findByDate(string $date): array;
    public function findByDateRange(string $startDate, string $endDate): array;
}
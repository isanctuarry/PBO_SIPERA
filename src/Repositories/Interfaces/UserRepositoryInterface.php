<?php
// ========================================
// File: src/Repositories/Interfaces/UserRepositoryInterface.php
// ========================================

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByUsername(string $username);
}
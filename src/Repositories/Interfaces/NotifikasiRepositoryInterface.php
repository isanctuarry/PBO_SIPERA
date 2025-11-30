<?php
namespace App\Repositories\Interfaces;

interface NotifikasiRepositoryInterface extends RepositoryInterface
{
    public function findByPegawaiId(int $pegawaiId): array;
}
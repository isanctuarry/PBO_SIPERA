<?php
// ========================================
// File: src/Factories/NotifikasiFactory.php
// ========================================

namespace App\Factories;

use App\Models\Notifikasi;

class NotifikasiFactory
{
    public static function createRapatBaru(int $pegawaiId, int $rapatId, string $judulRapat): Notifikasi
    {
        $notifikasi = new Notifikasi();
        $notifikasi->setPegawaiId($pegawaiId);
        $notifikasi->setRapatId($rapatId);
        $notifikasi->setPesan("Rapat baru dijadwalkan: {$judulRapat}");
        $notifikasi->setTimestamps();
        
        return $notifikasi;
    }

    public static function createRapatDiubah(int $pegawaiId, int $rapatId, string $judulRapat): Notifikasi
    {
        $notifikasi = new Notifikasi();
        $notifikasi->setPegawaiId($pegawaiId);
        $notifikasi->setRapatId($rapatId);
        $notifikasi->setPesan("Rapat diubah: {$judulRapat}");
        $notifikasi->setTimestamps();
        
        return $notifikasi;
    }

    public static function createRapatDibatalkan(int $pegawaiId, int $rapatId, string $judulRapat): Notifikasi
    {
        $notifikasi = new Notifikasi();
        $notifikasi->setPegawaiId($pegawaiId);
        $notifikasi->setRapatId($rapatId);
        $notifikasi->setPesan("Rapat dibatalkan: {$judulRapat}");
        $notifikasi->setTimestamps();
        
        return $notifikasi;
    }

    public static function createReminder(int $pegawaiId, int $rapatId, string $judulRapat, string $waktu): Notifikasi
    {
        $notifikasi = new Notifikasi();
        $notifikasi->setPegawaiId($pegawaiId);
        $notifikasi->setRapatId($rapatId);
        $notifikasi->setPesan("Reminder: Rapat '{$judulRapat}' akan dimulai pada {$waktu}");
        $notifikasi->setTimestamps();
        
        return $notifikasi;
    }

    public static function create(int $pegawaiId, ?int $rapatId, string $pesan): Notifikasi
    {
        $notifikasi = new Notifikasi();
        $notifikasi->setPegawaiId($pegawaiId);
        $notifikasi->setRapatId($rapatId);
        $notifikasi->setPesan($pesan);
        $notifikasi->setTimestamps();
        
        return $notifikasi;
    }
}
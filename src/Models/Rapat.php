<?php
namespace App\Models;

class Rapat
{
    public ?int $rapat_id = null;
    public string $judul;
    public ?string $deskripsi;
    public ?string $lokasi;
    public string $tanggal_mulai; // ISO datetime
    public string $tanggal_selesai;
    public int $dibuat_oleh;
    public ?int $diubah_oleh;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) $this->{$k} = $v;
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

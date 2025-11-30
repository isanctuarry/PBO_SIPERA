<?php

namespace App\Models;

class Rapat extends Model
{
    protected ?int $rapatId = null;
    protected string $judul;
    protected ?string $deskripsi = null;
    protected ?string $lokasi = null;
    protected string $tanggalMulai;
    protected string $tanggalSelesai;
    protected int $dibuatOleh;
    protected ?int $diubahOleh = null;

    public function getRapatId(): ?int { return $this->rapatId; }
    public function setRapatId(?int $id): self { $this->rapatId = $id; return $this; }
    
    public function getJudul(): string { return $this->judul; }
    public function setJudul(string $judul): self { $this->judul = $judul; return $this; }
    
    public function getDeskripsi(): ?string { return $this->deskripsi; }
    public function setDeskripsi(?string $deskripsi): self { $this->deskripsi = $deskripsi; return $this; }
    
    public function getLokasi(): ?string { return $this->lokasi; }
    public function setLokasi(?string $lokasi): self { $this->lokasi = $lokasi; return $this; }
    
    public function getTanggalMulai(): string { return $this->tanggalMulai; }
    public function setTanggalMulai(string $tanggal): self { $this->tanggalMulai = $tanggal; return $this; }
    
    public function getTanggalSelesai(): string { return $this->tanggalSelesai; }
    public function setTanggalSelesai(string $tanggal): self { $this->tanggalSelesai = $tanggal; return $this; }
    
    public function getDibuatOleh(): int { return $this->dibuatOleh; }
    public function setDibuatOleh(int $adminId): self { $this->dibuatOleh = $adminId; return $this; }
    
    public function getDiubahOleh(): ?int { return $this->diubahOleh; }
    public function setDiubahOleh(?int $adminId): self { $this->diubahOleh = $adminId; return $this; }

    public function validate(): array
    {
        $errors = [];
        
        if (empty($this->judul)) {
            $errors[] = "Judul rapat is required";
        }
        
        if (empty($this->tanggalMulai)) {
            $errors[] = "Tanggal mulai is required";
        }
        
        if (empty($this->tanggalSelesai)) {
            $errors[] = "Tanggal selesai is required";
        }
        
        if (strtotime($this->tanggalSelesai) < strtotime($this->tanggalMulai)) {
            $errors[] = "Tanggal selesai must be after tanggal mulai";
        }
        
        return $errors;
    }

    public function toArray(): array
    {
        return [
            'rapat_id' => $this->rapatId,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'lokasi' => $this->lokasi,
            'tanggal_mulai' => $this->tanggalMulai,
            'tanggal_selesai' => $this->tanggalSelesai,
            'dibuat_oleh' => $this->dibuatOleh,
            'diubah_oleh' => $this->diubahOleh,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }

    public function fromArray(array $data): self
    {
        if (isset($data['rapat_id'])) $this->rapatId = (int)$data['rapat_id'];
        if (isset($data['judul'])) $this->judul = $data['judul'];
        if (isset($data['deskripsi'])) $this->deskripsi = $data['deskripsi'];
        if (isset($data['lokasi'])) $this->lokasi = $data['lokasi'];
        if (isset($data['tanggal_mulai'])) $this->tanggalMulai = $data['tanggal_mulai'];
        if (isset($data['tanggal_selesai'])) $this->tanggalSelesai = $data['tanggal_selesai'];
        if (isset($data['dibuat_oleh'])) $this->dibuatOleh = (int)$data['dibuat_oleh'];
        if (isset($data['diubah_oleh'])) $this->diubahOleh = $data['diubah_oleh'];
        if (isset($data['created_at'])) $this->createdAt = $data['created_at'];
        if (isset($data['updated_at'])) $this->updatedAt = $data['updated_at'];
        
        return $this;
    }
}
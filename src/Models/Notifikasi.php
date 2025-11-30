<?php

namespace App\Models;

class Notifikasi extends Model
{
    protected ?int $notifikasiId = null;
    protected int $pegawaiId;
    protected ?int $rapatId = null;
    protected string $pesan;

    public function getNotifikasiId(): ?int { return $this->notifikasiId; }
    public function setNotifikasiId(?int $id): self { $this->notifikasiId = $id; return $this; }
    
    public function getPegawaiId(): int { return $this->pegawaiId; }
    public function setPegawaiId(int $id): self { $this->pegawaiId = $id; return $this; }
    
    public function getRapatId(): ?int { return $this->rapatId; }
    public function setRapatId(?int $id): self { $this->rapatId = $id; return $this; }
    
    public function getPesan(): string { return $this->pesan; }
    public function setPesan(string $pesan): self { $this->pesan = $pesan; return $this; }

    public function validate(): array
    {
        $errors = [];
        if (empty($this->pesan)) $errors[] = "Pesan is required";
        return $errors;
    }

    public function toArray(): array
    {
        return [
            'notifikasi_id' => $this->notifikasiId,
            'pegawai_id' => $this->pegawaiId,
            'rapat_id' => $this->rapatId,
            'pesan' => $this->pesan,
            'created_at' => $this->createdAt
        ];
    }

    public function fromArray(array $data): self
    {
        if (isset($data['notifikasi_id'])) $this->notifikasiId = (int)$data['notifikasi_id'];
        if (isset($data['pegawai_id'])) $this->pegawaiId = (int)$data['pegawai_id'];
        if (isset($data['rapat_id'])) $this->rapatId = $data['rapat_id'];
        if (isset($data['pesan'])) $this->pesan = $data['pesan'];
        if (isset($data['created_at'])) $this->createdAt = $data['created_at'];
        
        return $this;
    }
}
<?php

namespace App\Models;

class Pegawai extends User
{
    protected ?int $rapatId = null;

    public function __construct()
    {
        $this->userType = 'pegawai';
    }

    public function getRole(): string
    {
        return 'PEGAWAI';
    }

    public function getRapatId(): ?int
    {
        return $this->rapatId;
    }

    public function setRapatId(?int $rapatId): self
    {
        $this->rapatId = $rapatId;
        return $this;
    }

    public function fromArray(array $data): self
    {
        if (isset($data['pegawai_id'])) $this->id = (int)$data['pegawai_id'];
        if (isset($data['username'])) $this->username = $data['username'];
        if (isset($data['password'])) $this->password = $data['password'];
        if (isset($data['rapat_id'])) $this->rapatId = $data['rapat_id'];
        if (isset($data['created_at'])) $this->createdAt = $data['created_at'];
        if (isset($data['updated_at'])) $this->updatedAt = $data['updated_at'];
        
        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['rapat_id'] = $this->rapatId;
        return $data;
    }
}
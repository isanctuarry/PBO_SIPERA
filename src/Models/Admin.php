<?php

namespace App\Models;

class Admin extends User
{
    public function __construct()
    {
        $this->userType = 'admin';
    }

    public function getRole(): string
    {
        return 'ADMIN';
    }

    public function fromArray(array $data): self
    {
        if (isset($data['admin_id'])) $this->id = (int)$data['admin_id'];
        if (isset($data['username'])) $this->username = $data['username'];
        if (isset($data['password'])) $this->password = $data['password'];
        if (isset($data['created_at'])) $this->createdAt = $data['created_at'];
        if (isset($data['updated_at'])) $this->updatedAt = $data['updated_at'];
        
        return $this;
    }

    public function canManageRapat(): bool
    {
        return true;
    }
}
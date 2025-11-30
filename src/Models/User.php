<?php

namespace App\Models;

abstract class User extends Model
{
    protected ?int $id = null;
    protected string $username;
    protected string $password;
    protected string $userType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    abstract public function getRole(): string;

    public function validate(): array
    {
        $errors = [];
        
        if (empty($this->username)) {
            $errors[] = "Username is required";
        }
        
        if (strlen($this->username) < 3) {
            $errors[] = "Username must be at least 3 characters";
        }
        
        return $errors;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'user_type' => $this->userType,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}
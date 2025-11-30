<?php
// ========================================
// File: src/Services/AuthService.php
// ========================================

namespace App\Services;

use App\Repositories\UserRepository;
use App\Utils\JWTHandler;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;

class AuthService
{
    private UserRepository $userRepository;
    private JWTHandler $jwtHandler;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->jwtHandler = new JWTHandler();
    }

    public function login(string $username, string $password): array
    {
        if (empty($username) || empty($password)) {
            throw new ValidationException('Username and password are required');
        }

        $user = $this->userRepository->findByUsername($username);
        
        if (!$user) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!$user->verifyPassword($password)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        $payload = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => $user->getRole(),
            'user_type' => $user->getUserType()
        ];

        $token = $this->jwtHandler->encode($payload);

        return [
            'token' => $token,
            'user' => $user->toArray(),
            'expires_in' => $_ENV['JWT_EXPIRATION'] ?? 3600
        ];
    }

    public function verifyToken(string $token): array
    {
        $payload = $this->jwtHandler->getPayload($token);
        
        if (!$payload) {
            throw new UnauthorizedException('Invalid or expired token');
        }

        return $payload;
    }

    public function refreshToken(string $token): string
    {
        $payload = $this->verifyToken($token);
        return $this->jwtHandler->encode($payload);
    }
}
<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTHandler
{
    private string $secret;
    private int $expiration;
    private string $algorithm = 'HS256';

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'default_secret_key';
        $this->expiration = (int)($_ENV['JWT_EXPIRATION'] ?? 3600);
    }

    public function encode(array $payload): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expiration;

        $token = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $payload
        ];

        return JWT::encode($token, $this->secret, $this->algorithm);
    }

    public function decode(string $token): object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (Exception $e) {
            throw new Exception('Invalid token: ' . $e->getMessage());
        }
    }

    public function verify(string $token): bool
    {
        try {
            $this->decode($token);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPayload(string $token): ?array
    {
        try {
            $decoded = $this->decode($token);
            return (array)$decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getBearerToken(): ?string
    {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
}
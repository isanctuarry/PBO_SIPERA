<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Jwt
{
    private static string $key = 'change_this_secret_please';
    private static string $algo = 'HS256';
    private static int $exp = 3600 * 24;

    public static function encode(array $payload): string
    {
        $now = time();
        $token = array_merge($payload, [
            'iat' => $now,
            'exp' => $now + self::$exp
        ]);
        return JWT::encode($token, self::$key, self::$algo);
    }

    public static function decode(string $jwt): object
    {
        try {
            return JWT::decode($jwt, new Key(self::$key, self::$algo));
        } catch (Exception $e) {
            throw new \App\Exceptions\HttpException(401, 'Invalid token: ' . $e->getMessage());
        }
    }
}

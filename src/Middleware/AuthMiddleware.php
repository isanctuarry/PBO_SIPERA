<?php
namespace App\Middleware;

use App\Utils\JWTHandler;
use App\Utils\ApiResponse;
use App\Exceptions\UnauthorizedException;

class AuthMiddleware
{
    private JWTHandler $jwtHandler;

    public function __construct()
    {
        $this->jwtHandler = new JWTHandler();
    }

    public function Handle(): array 
    {
        try {
            $token = JWTHandler::getBearerToken();

            if (!$token) {
                throw new UnauthorizedException('Token not provided');
            }

            $payload = $this->jwtHandler->getPayload($token);

            if (!$payload) {
                throw new UnauthorizedException('Invalid or expired token');
            }

            return $payload;
        } catch (UnauthorizedException $e) {
            ApiResponse::unauthorized($e->getMessage())->send();
        } catch (\Exception $e) {
            ApiResponse::unauthorized('Authentication failed')->send();
        }
    }
}
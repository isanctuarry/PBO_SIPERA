<?php 
namespace App\Middleware;

use App\Utils\ApiResponse;

class AdminMiddleware
{
    public function handle(array $user): void
    {
        if (!isset($user['role']) || $user['role'] !== 'ADMIN') {
            ApiResponse::forbidden('Admin access required')->send();
        }
    }
}

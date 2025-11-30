<?php
// ========================================
// File: src/Controllers/AuthController.php
// ========================================

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;
use App\Utils\ApiResponse;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(): void
    {
        try {
            $data = $this->getJsonInput();

            $result = $this->authService->login(
                $data['username'] ?? '',
                $data['password'] ?? ''
            );

            ApiResponse::ok($result, 'Login successful')->send();
        } catch (UnauthorizedException $e) {
            ApiResponse::unauthorized($e->getMessage())->send();
        } catch (ValidationException $e) {
            ApiResponse::badRequest($e->getMessage(), $e->getErrors())->send();
        } catch (\Exception $e) {
            ApiResponse::internalError($e->getMessage())->send();
        }
    }

    public function logout(): void
    {
        ApiResponse::ok(null, 'Logout successful')->send();
    }
}
<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function login(Request $request): Response
    {
        $data = $request->body;

        $this->auth->validateLogin($data);

        $result = $this->auth->login($data['username'], $data['password']);

        return new Response([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => $result
        ], 200);
    }
}
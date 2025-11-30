<?php

use Src\Core\Router;
use Src\Controllers\AuthController;
use Src\Controllers\PegawaiController;
use Src\Controllers\RapatController;
use Src\Controllers\NotifikasiController;

$router = new Router();

// Auth Routes
$router->post('auth/login', [authController::class, 'login']);
$router->post('auth/register', [authController::class, 'register']);

// Pegawai Routes
$router->get('/api/v1/pegawai', [PegawaiController::class, 'index']);

// Rapat Routes
$router->get('/api/v1/rapat', [RapatController::class, 'index']);

// Notifikasi Routes
$router->get('/api/v1/notifikasi', [NotifikasiController::class, 'index']);

return $router;

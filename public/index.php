<?php 
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\RapatController;
use App\Controllers\PegawaiController;
use App\Controllers\NotifikasiController;
use App\Services\AuthService;
use App\Services\RapatService;
use App\Services\NotifikasiService;
use App\Repositories\UserRepository;
use App\Repositories\RapatRepository;
use App\Repositories\NotifikasiRepository;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\CorsMiddleware;
use App\Utils\ApiResponse;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');

// Handle CORS
$corsMiddleware = new CorsMiddleware();
$corsMiddleware->handle();

// Error handling
set_exception_handler(function ($exception) {
    error_log($exception->getMessage());
    
    if ($_ENV['APP_DEBUG'] === 'true') {
        ApiResponse::internalError($exception->getMessage())->send();
    } else {
        ApiResponse::internalError('An error occurred')->send();
    }
});

// Dependency Injection Container
$userRepository = new UserRepository();
$rapatRepository = new RapatRepository();
$notifikasiRepository = new NotifikasiRepository();

$authService = new AuthService($userRepository);
$notifikasiService = new NotifikasiService($notifikasiRepository);
$rapatService = new RapatService($rapatRepository, $notifikasiRepository, $notifikasiService);

$authController = new AuthController($authService);
$rapatController = new RapatController($rapatService);
$pegawaiController = new PegawaiController($rapatService, $notifikasiService);
$notifikasiController = new NotifikasiController($notifikasiService);

// Initialize Router
$router = new Router('/api/v1');

// ============================================
// AUTH ROUTES
// ============================================
$router->post('/auth/login', [$authController, 'login']);
$router->post('/auth/logout', [$authController, 'logout'], [AuthMiddleware::class]);

// ============================================
// RAPAT ROUTES
// ============================================
$router->get('/rapat', [$rapatController, 'index'], [AuthMiddleware::class]);
$router->get('/rapat/{id}', [$rapatController, 'show'], [AuthMiddleware::class]);

$router->post('/rapat', function ($user) use ($rapatController) {
    $rapatController->store($user);
}, [AuthMiddleware::class, AdminMiddleware::class]);

$router->put('/rapat/{id}', function ($id, $user) use ($rapatController) {
    $rapatController->update((int)$id, $user);
}, [AuthMiddleware::class, AdminMiddleware::class]);

$router->delete('/rapat/{id}', function ($id) use ($rapatController) {
    $rapatController->destroy((int)$id);
}, [AuthMiddleware::class, AdminMiddleware::class]);

// ============================================
// PEGAWAI ROUTES
// ============================================
$router->get('/pegawai/jadwal', [$pegawaiController, 'getJadwal'], [AuthMiddleware::class]);

$router->get('/pegawai/notifikasi', function ($user) use ($pegawaiController) {
    $pegawaiController->getNotifikasi($user);
}, [AuthMiddleware::class]);

// ============================================
// NOTIFIKASI ROUTES
// ============================================
$router->post('/notifikasi/kirim', [$notifikasiController, 'kirim'], [AuthMiddleware::class, AdminMiddleware::class]);

$router->delete('/notifikasi/{id}', function ($id) use ($notifikasiController) {
    $notifikasiController->destroy((int)$id);
}, [AuthMiddleware::class]);

// ============================================
// ROOT ENDPOINT
// ============================================
$router->get('/', function () {
    ApiResponse::ok([
        'name' => 'Sistem Penjadwalan Rapat API',
        'version' => '1.0.0',
        'description' => 'REST API untuk manajemen jadwal rapat',
        'documentation' => '/docs/openapi.yaml',
        'endpoints' => [
            'auth' => '/api/v1/auth/*',
            'rapat' => '/api/v1/rapat/*',
            'pegawai' => '/api/v1/pegawai/*',
            'notifikasi' => '/api/v1/notifikasi/*'
        ]
    ], 'API is running')->send();
});

// Dispatch request
$router->dispatch();
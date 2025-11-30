<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\{App, Router, Request, ExceptionHandler};

$app = new App();

$request = Request::capture();

$handler = new ExceptionHandler();
try {
    $router = new Router($request);
    $router->loadRoutes();
    $response = $router->dispatch();
    $response->send();
} catch (\Throwable $e) {
    $handler->render($e);
}

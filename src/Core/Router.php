<?php
namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\RapatController;

class Router
{
    private Request $request;
    private array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function loadRoutes(): void
    {
        /**
         * ============================
         *  ROUTE DEFAULT ("/")
         * ============================
         */
        $this->routes['GET:/'] = function () {
            return new Response([
                'status' => 'success',
                'message' => 'API SIPERA berjalan',
                'data' => [
                    'endpoints' => [
                        'POST /auth/login',
                        'GET /rapat',
                        'POST /rapat',
                        'PUT /rapat/{id}',
                        'DELETE /rapat/{id}',
                    ]
                ]
            ], 200);
        };

        /**
         * ============================
         *  AUTH ENDPOINTS
         * ============================
         */
        $this->routes['POST:/auth/login']  = [AuthController::class, 'login'];
        $this->routes['POST:/auth/logout'] = [AuthController::class, 'logout'];

        /**
         * ============================
         *  RAPAT ENDPOINTS
         * ============================
         */
        $this->routes['GET:/rapat']          = [RapatController::class, 'index'];
        $this->routes['GET:/rapat/{id}']     = [RapatController::class, 'show'];
        $this->routes['POST:/rapat']         = [RapatController::class, 'store'];
        $this->routes['PUT:/rapat/{id}']     = [RapatController::class, 'update'];
        $this->routes['DELETE:/rapat/{id}']  = [RapatController::class, 'delete'];
    }

    public function dispatch(): Response
    {
        $method = $this->request->method;
        $path = rtrim($this->request->path, '/');
        if ($path === '') $path = '/';

        foreach ($this->routes as $routeKey => $handler) {
            [$rMethod, $rPath] = explode(':', $routeKey, 2);

            $rPath = rtrim($rPath, '/') ?: '/';

            // Ubah {id} menjadi regex angka
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $rPath);
            $pattern = "#^" . $pattern . "$#";

            if ($rMethod === $method && preg_match($pattern, $path, $matches)) {

                array_shift($matches);

                // Handler closure?
                if (is_callable($handler)) {
                    return $handler();
                }

                // Handler class controller
                [$class, $methodName] = $handler;
                $controller = new $class();

                return call_user_func_array([$controller, $methodName], array_merge([$this->request], $matches));
            }
        }

        throw new \App\Exceptions\HttpException(404, 'Endpoint not found');
    }
}

<?php
namespace App\Core;

use Throwable;
use App\Exceptions\HttpException;

class ExceptionHandler
{
    public function render(Throwable $e): void
    {
        $status = 500;
        $message = 'Internal Server Error';
        $data = null;

        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $message = $e->getMessage();
            if ($e instanceof \App\Exceptions\ValidationException) {
                $data = ['errors' => $e->getErrors()];
            }
        } else {
            // For dev, you can include trace; in production hide it
            $message = $e->getMessage();
        }

        $payload = [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

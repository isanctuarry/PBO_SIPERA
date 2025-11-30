<?php
namespace App\Core;

class Response
{
    protected array $payload;
    protected int $status;

    public function __construct(array $payload = [], int $status = 200)
    {
        $this->payload = $payload;
        $this->status = $status;
    }

    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->payload, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}

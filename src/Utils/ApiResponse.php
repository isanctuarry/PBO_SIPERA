<?php
namespace App\Utils;

class ApiResponse
{
    private string $status = 'success';
    private ?string $message = null;
    private mixed $data = null;
    private ?array $errors = null;
    private ?array $meta = null;
    private int $httpCode = 200;

    public function success(): self
    {
        $this->status = 'success';
        $this->httpCode = 200;
        return $this;
    }

    public function error(): self
    {
        $this->status = 'error';
        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function data(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function errors(array $errors): self
    {
        $this->errors = $errors;
        $this->error();
        return $this;
    }

    public function meta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function httpCode(int $code): self
    {
        $this->httpCode = $code;
        return $this;
    }

    public function build(): array
    {
        $response = [
            'status' => $this->status
        ];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        if ($this->meta !== null) {
            $response['meta'] = $this->meta;
        }

        return $response;
    }

    public function send(): void
    {
        http_response_code($this->httpCode);
        header('Content-Type: application/json');
        echo json_encode($this->build(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function ok(mixed $data = null, string $message = 'Success'): self
    {
        return (new self())
            ->success()
            ->message($message)
            ->data($data)
            ->httpCode(200);
    }

    public static function created(mixed $data = null, string $message = 'Resource created'): self
    {
        return (new self())
            ->success()
            ->message($message)
            ->data($data)
            ->httpCode(201);
    }

    public static function noContent(): self
    {
        return (new self())
            ->success()
            ->httpCode(204);
    }

    public static function badRequest(string $message = 'Bad request', array $errors = []): self
    {
        $response = (new self())
            ->error()
            ->message($message)
            ->httpCode(400);
        
        if (!empty($errors)) {
            $response->errors($errors);
        }
        
        return $response;
    }

    public static function unauthorized(string $message = 'Unauthorized'): self
    {
        return (new self())
            ->error()
            ->message($message)
            ->httpCode(401);
    }

    public static function forbidden(string $message = 'Forbidden'): self
    {
        return (new self())
            ->error()
            ->message($message)
            ->httpCode(403);
    }

    public static function notFound(string $message = 'Resource not found'): self
    {
        return (new self())
            ->error()
            ->message($message)
            ->httpCode(404);
    }

    public static function internalError(string $message = 'Internal server error'): self
    {
        return (new self())
            ->error()
            ->message($message)
            ->httpCode(500);
    }
}
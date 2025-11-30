<?php

namespace App\Exceptions;

class ValidationException extends AppException
{
    private array $errors = [];

    public function __construct(string $message = "Validation failed", array $errors = [], int $code = 400)
    {
        parent::__construct($message, $code, 400);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
<?php
namespace App\Exceptions;

class ValidationException extends HttpException
{
    private array $errors;
    public function __construct(array $errors, string $message = 'Validation failed')
    {
        parent::__construct(422, $message);
        $this->errors = $errors;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}

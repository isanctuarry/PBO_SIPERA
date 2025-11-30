<?php
namespace App\Exceptions;

class BadRequestException extends AppException
{
    public function __construct(string $message = "Bad request", int $code = 400)
    {
        parent::__construct($message, $code, 400);
    }
}
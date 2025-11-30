<?php
namespace App\Exceptions;

class ForbiddenException extends AppException
{
    public function __construct(string $message = "Forbidden", int $code = 403)
    {
        parent::__construct($message, $code, 403);
    }
}
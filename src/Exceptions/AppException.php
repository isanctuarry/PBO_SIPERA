<?php
namespace App\Exceptions;

use Exception;

abstract class AppException extends Exception
{
    protected int $httpCode;

    public function __construct(string $message = "", int $code = 0, int $httpCode = 500)
    {
        parent::__construct($message, $code);
        $this->httpCode = $httpCode;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
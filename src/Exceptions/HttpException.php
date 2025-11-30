<?php
namespace App\Exceptions;

class HttpException extends \Exception
{
    protected int $statusCode;

    public function __construct(int $statusCode = 500, string $message = 'Server Error')
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

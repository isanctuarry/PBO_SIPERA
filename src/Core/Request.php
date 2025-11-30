<?php
namespace App\Core;

class Request
{
    public string $method;
    public array $query;
    public array $body;
    public array $headers;
    public string $path;

    private function __construct() {}

    public static function capture(): self
    {
        $req = new self();
        $req->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $req->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $req->query = $_GET;
        $input = file_get_contents('php://input');
        $req->body = json_decode($input ?: '{}', true) ?? [];
        $req->headers = getallheaders() ?: [];
        return $req;
    }

    public function header(string $name, $default = null)
    {
        return $this->headers[$name] ?? $this->headers[strtolower($name)] ?? $default;
    }
}

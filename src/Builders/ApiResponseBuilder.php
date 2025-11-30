<?php
namespace App\Builders;

class ApiResponseBuilder
{
    private string $status = 'success';
    private string $message = '';
    private $data = null;

    public static function make(): self
    {
        return new self();
    }

    public function status(string $s): self { $this->status = $s; return $this; }
    public function message(string $m): self { $this->message = $m; return $this; }
    public function data($d): self { $this->data = $d; return $this; }

    public function build(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}

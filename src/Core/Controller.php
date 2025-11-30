<?php
namespace App\Core;

abstract class Controller
{
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }

    protected function getQueryParams(): array
    {
        return $_GET;
    }
}
<?php

namespace App\Models;

use App\Traits\Timestampable;

abstract class Model
{
    use Timestampable;

    abstract public function toArray(): array;
    abstract public function fromArray(array $data): self;
    abstract public function validate(): array;
}
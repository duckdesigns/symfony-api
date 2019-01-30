<?php
declare(strict_types = 1);

namespace App\Valueobject\Header;

class Location extends Header
{

    public static function create(string $path): self
    {
        return new self($path);
    }

    public function toArray(): array
    {
        return ['Location' => $this->value];
    }
}


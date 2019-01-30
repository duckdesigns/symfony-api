<?php
declare(strict_types = 1);

namespace App\Valueobject\Header;

class ContentType extends Header
{

    public static function createJson(): self
    {
        return new self('application/json');
    }

    public function toArray(): array
    {
        return ['Content-Type' => $this->value];
    }
}


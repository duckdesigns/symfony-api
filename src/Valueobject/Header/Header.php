<?php
declare(strict_types = 1);

namespace App\Valueobject\Header;

abstract class Header
{

    protected $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    abstract public function toArray(): array;
}

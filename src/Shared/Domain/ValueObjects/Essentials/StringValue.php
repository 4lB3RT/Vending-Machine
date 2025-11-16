<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects\Essentials;

abstract readonly class StringValue
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}

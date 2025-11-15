<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects;

use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue as Validator;

abstract readonly class UuidValue
{
    /* @throws InvalidUuid */
    public function __construct(
        private Validator $validator,
        protected string  $value
    ) {
        $this->validator->isValid($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}

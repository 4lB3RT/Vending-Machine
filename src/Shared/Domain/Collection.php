<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain;

use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Validators\Collection as Validator;

abstract class Collection
{
    /* @throws InvalidCollectionType */
    public function __construct(
        private readonly Validator $validator,
        protected array            $items
    ) {
        $this->validator->isValid($items, $this->type());
    }

    abstract protected function type(): string;

    public function items(): array
    {
        return $this->items;
    }
}

<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\Collections;

use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;

abstract class Collection
{
    /* @throws InvalidCollectionType */
    public function __construct(
        protected array $items
    ) {
        $type = $this->type();

        foreach ($items as $item) {
            if (!$item instanceof $type) {
                throw InvalidCollectionType::create();
            }
        }
    }

    abstract protected function type(): string;

    public function items(): array
    {
        return $this->items;
    }
}

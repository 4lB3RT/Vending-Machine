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

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items());
    }

    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function ids(): array
    {
        $itemsIds = [];
        foreach ($this->items() as $item) {
            $itemsIds[] = (int) $item->id()->value();
        }

        return $itemsIds;
    }
}

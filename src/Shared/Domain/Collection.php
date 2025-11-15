<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain;

use Webmozart\Assert\Assert;

abstract class Collection
{
    public function __construct(protected array $items)
    {
        Assert::allIsInstanceOf($items, $this->type());
    }

    abstract protected function type(): string;

    public function items(): array
    {
        return $this->items;
    }
}

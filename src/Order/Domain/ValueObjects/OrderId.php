<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Domain\ValueObjects;

use VendingMachine\Shared\Domain\ValueObjects\Essentials\UuidValue;

final readonly class OrderId extends UuidValue
{
}

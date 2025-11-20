<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;

final class WalletNotFound extends EntityNotFound
{
    private const MESSAGE = 'Wallet not found: %s';

    public static function create(string $walletId): self
    {
        return new self(sprintf(self::MESSAGE, $walletId));
    }
}

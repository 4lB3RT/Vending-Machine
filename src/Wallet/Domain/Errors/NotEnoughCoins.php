<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Errors;

use Exception;

final class NotEnoughCoins extends Exception
{
    public static function create(float $total, float $coins): self
    {
        return new self("Not enough coins in wallet: required $total, available $coins");
    }
}

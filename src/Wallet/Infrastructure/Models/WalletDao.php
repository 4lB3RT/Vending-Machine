<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Models;

use Database\Factories\WalletDaoFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property float  $coins
 */
final class WalletDao extends Model
{
    public $incrementing  = false;
    protected $table      = 'wallets';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $fillable   = [
        'id', 'name', 'coins',
    ];

    public static function factory(): WalletDaoFactory
    {
        return new WalletDaoFactory();
    }
}

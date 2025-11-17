<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property float  $price
 * @property int    $quantity
 */
final class ProductDao extends Model
{
    public $incrementing  = false;
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $fillable   = [
        'id', 'name', 'price', 'quantity',
    ];
}

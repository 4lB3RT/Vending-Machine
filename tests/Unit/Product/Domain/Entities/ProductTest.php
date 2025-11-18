<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\Entities;

use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testProductIsCreated(): void
    {
        $uuid     = '123e4567-e89b-12d3-a456-426614174000';
        $name     = 'Coke';
        $price    = 1.5;
        $quantity = 10;
        $product  = ProductMother::create($uuid, $name, $price, $quantity);

        $this->assertEquals($uuid, $product->id()->value());
        $this->assertEquals($name, $product->name()->value());
        $this->assertEquals($price, $product->price()->value());
        $this->assertEquals($quantity, $product->quantity()->value());
    }

    public function testToArrayReturnsCorrectValues(): void
    {
        $uuid     = '123e4567-e89b-12d3-a456-426614174000';
        $name     = 'Coke';
        $price    = 1.5;
        $quantity = 10;
        $product  = ProductMother::create($uuid, $name, $price, $quantity);

        $expected = [
            'id'       => $uuid,
            'name'     => $name,
            'price'    => $price,
            'quantity' => $quantity,
        ];

        $this->assertEquals($expected, $product->toArray());
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VendingMachine\Product\Infrastructure\Models\ProductDao;
use Illuminate\Support\Str;

class ProductDaoFactory extends Factory
{
    protected $model = ProductDao::class;

    public function definition(): array
    {
        return [
            'id'       => (string) Str::uuid(),
            'name'     => $this->faker->word(),
            'price'    => $this->faker->randomFloat(2, 0.5, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}


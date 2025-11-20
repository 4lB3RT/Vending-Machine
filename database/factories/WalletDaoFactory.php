<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VendingMachine\Wallet\Infrastructure\Models\WalletDao;

class WalletDaoFactory extends Factory
{
    protected $model = WalletDao::class;

    public function definition(): array
    {
        return [
            'id'    => $this->faker->uuid(),
            'name'  => $this->faker->name(),
            'coins' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}


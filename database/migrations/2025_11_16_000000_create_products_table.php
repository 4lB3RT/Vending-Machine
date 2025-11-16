<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255)->index();
            $table->float('price');
            $table->integer('quantity');
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'id' => (string) Str::uuid(),
                'name' => 'Water',
                'price' => 0.65,
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Juice',
                'price' => 1.00,
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Soda',
                'price' => 1.50,
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

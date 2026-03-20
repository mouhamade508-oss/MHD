<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::inRandomOrder()->take(5)->get()->each(function ($product) {
            \App\Models\DiscountCode::create([
                'code' => 'SAVE' . rand(10, 99),
                'product_id' => $product->id,
                'percentage' => rand(10, 50),
                'uses_limit' => rand(5, 20),
                'expires_at' => now()->addDays(rand(7, 30)),
                'status' => 'active',
            ]);
        });
    }
}


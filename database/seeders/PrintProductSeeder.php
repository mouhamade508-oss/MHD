<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PrintProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        $categories = Category::all();
        
        $images = [
            'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=500&fit=crop', // Print mockups
            'https://images.unsplash.com/photo-1581237717398-15d268bd20d8?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1580489944761-10a60cb331d9?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=500&fit=crop',
        ];

        for ($i = 0; $i < 24; $i++) {
            $category = $categories->random();
            
            Product::create([
                'name' => $faker->words(3, true),
                'description' => $faker->paragraph(3),
                'price' => $faker->numberBetween(15000, 120000),
                'image' => 'products/' . $faker->uuid . '.jpg',
                'available_colors' => json_encode(['#000000', '#ffffff', '#ff0000', '#0000ff']),
                'sizes_available' => json_encode(['A4', 'A3', 'A2']),
                'stock_per_variant' => $faker->numberBetween(20, 100),
                'category_id' => $category->id,
                'featured' => $faker->boolean(30),
                'specifications' => 'طباعة عالية الجودة على ورق فاخر',
            ]);
        }
    }
}


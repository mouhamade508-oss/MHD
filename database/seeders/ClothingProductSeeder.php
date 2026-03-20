<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClothingProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        $categories = Category::all();
        
        $images = [
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=500&fit=crop',
            'https://images.unsplash.com/photo-1594736797933-d0db5eb6f214?w=400&h=500&fit=crop',
        ];

        $clothingColors = ['#e94560', '#4a90e2', '#7ed321', '#f5a623', '#9013fe', '#50e3c2'];
        $clothingSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        for ($i = 0; $i < 24; $i++) {
            $category = $categories->random();
            
            Product::create([
                'name' => $faker->words(3, true),
                'description' => $faker->paragraph(3),
                'price' => $faker->numberBetween(15000, 120000),
                'image' => 'products/' . $faker->uuid . '.jpg',
                'available_colors' => json_encode(array_slice($clothingColors, 0, $faker->numberBetween(2,4))),
                'sizes_available' => json_encode(array_slice($clothingSizes, 0, $faker->numberBetween(3,5))),
                'stock_per_variant' => $faker->numberBetween(5, 50),
                'category_id' => $category->id,
                'featured' => $faker->boolean(30),
                'specifications' => $faker->paragraph(2),
            ]);
        }
    }
}


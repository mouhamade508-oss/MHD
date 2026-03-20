<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'تيشرتات',
                'slug' => 'tshirts',
                'description' => 'جميع أنواع التيشرتات العصرية',
                'color' => '#e94560',
            ],
            [
                'name' => 'هودي',
                'slug' => 'hoodies',
                'description' => 'هودي دافئ وأنيق',
                'color' => '#0f3460',
            ],
            [
                'name' => 'بناطيل',
                'slug' => 'pants',
                'description' => 'جينز وبناطيل كاجوال',
                'color' => '#f0932b',
            ],
            [
                'name' => 'فساتين',
                'slug' => 'dresses',
                'description' => 'فساتين سهرة ويومية',
                'color' => '#eb4d4b',
            ],
            [
                'name' => 'أحذية',
                'slug' => 'shoes',
                'description' => 'أحذية رياضية وكلاسيك',
                'color' => '#6c5ce7',
            ],
            [
                'name' => 'إكسسوارات',
                'slug' => 'accessories',
                'description' => 'محافظ ونظارات وحزام',
                'color' => '#00b894',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}


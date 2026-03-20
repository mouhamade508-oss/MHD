<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = \App\Models\User::inRandomOrder()->limit(5)->pluck('id')->toArray();
        
        \App\Models\Review::factory(20)->create([
            'product_id' => 25,
            'user_id' => $users[array_rand($users)], 
            'approved' => true
        ]);
    }
}


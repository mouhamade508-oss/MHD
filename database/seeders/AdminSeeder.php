<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'mouhamad.deop2000@gmail.com'],
            [
                'name'     => 'Mouhamad Admin',
                'email'    => 'mouhamad.deop2000@gmail.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created: mouhamad.deop2000@gmail.com / admin123');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'lokesh.kumar.wh@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'status' => 1,
        ]);

        // Assign "admin" role
        $admin->assignRole('admin');
    }
}

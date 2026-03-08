<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Natan',
            'email' => 'c14240154@john.petra.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);
    }
}

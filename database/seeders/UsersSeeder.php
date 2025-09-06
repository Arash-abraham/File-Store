<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'phone' => '09120000000',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'avatar_url' => null,
                'birthdate' => null,
                'email_verified_at' => $now,
                'remember_token' => Str::random(20),
                'marketing_opt_in' => false,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'first_name' => 'Ali',
                'last_name' => 'Ahmadi',
                'email' => 'user@example.com',
                'phone' => '09123334444',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar_url' => null,
                'birthdate' => '1995-05-20',
                'email_verified_at' => null,
                'remember_token' => Str::random(20),
                'marketing_opt_in' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);
    }
}


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
        $adminEmail = mb_strtolower(env('DEMO_ADMIN_EMAIL', 'admin@example.com'));
        $adminPassword = env('DEMO_ADMIN_PASSWORD', 'password');
        $customerEmail = mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com'));
        $customerPassword = env('DEMO_USER_PASSWORD', 'password');

        DB::table('users')->updateOrInsert(['email' => $adminEmail], [
            'name' => 'مدیر فایل‌استور',
            'phone' => '09120000000',
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'avatar_url' => null,
            'birthdate' => null,
            'email_verified_at' => $now,
            'remember_token' => Str::random(60),
            'marketing_opt_in' => false,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);

        DB::table('users')->updateOrInsert(['email' => $customerEmail], [
            'name' => 'آرمان رضایی',
            'phone' => '09123334444',
            'password' => Hash::make($customerPassword),
            'role' => 'user',
            'avatar_url' => null,
            'birthdate' => '1995-05-20',
            'email_verified_at' => $now,
            'remember_token' => Str::random(60),
            'marketing_opt_in' => true,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
    }
}

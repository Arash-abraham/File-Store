<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CategoriesSeeder::class,
            TagsSeeder::class,
            CouponsSeeder::class,
            ProductsSeeder::class,
            ProductFilesSeeder::class,
            ProductTagSeeder::class,
            FaqsSeeder::class,
            SettingsSeeder::class,
            MenusSeeder::class,
            OrdersSeeder::class,
            OrderItemsSeeder::class,
            PaymentsSeeder::class,
            CartsSeeder::class,
            CartItemsSeeder::class,
            TicketsSeeder::class,
            ProductReviewsSeeder::class,
        ]);
    }
}

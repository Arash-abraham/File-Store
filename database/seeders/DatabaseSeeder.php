<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CategoriesSeeder::class,
            TagsSeeder::class,
            CouponsSeeder::class,
            ProductsSeeder::class,
            ProductTagSeeder::class,
            ProductFilesSeeder::class,
            SettingsSeeder::class,
            WebSettingsSeeder::class,
            FaqsSeeder::class,
            PagesSeeder::class,
            MenusSeeder::class,
            WalletSeeder::class,
            CartsSeeder::class,
            CartItemsSeeder::class,
            OrdersSeeder::class,
            OrderItemsSeeder::class,
            PaymentsSeeder::class,
            TicketsSeeder::class,
            ProductReviewsSeeder::class,
        ]);
    }
}

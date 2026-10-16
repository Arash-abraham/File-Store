<?php

namespace Database\Seeders;

use App\Models\PrintHouse;
use App\Models\Product;
use App\Models\User;
use App\Services\PrintPricingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrintOrdersSeeder extends Seeder
{
    /**
     * Two print orders for the demo reader, so the "سفارش‌های چاپی" tab of the
     * account area has something to show. The prices are calculated with the
     * same service the shop uses, so the seeded orders can never drift from the
     * real price list.
     */
    public function run(): void
    {
        $user = User::query()
            ->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))
            ->first();

        if (! $user) {
            return;
        }

        $plans = [
            [
                'house' => 'naqsh-jahan',
                'product' => 'linux-for-hackers',
                'options' => ['quantity' => 1, 'paper_type' => 'premium', 'binding' => 'hardcover', 'color_print' => false],
                'owns_digital' => true,
                'status' => 'printing',
                'tracking_code' => null,
                'created_at' => now()->subDays(3),
            ],
            [
                'house' => 'modern-shiraz',
                'product' => 'nader-shah-afshar',
                'options' => ['quantity' => 2, 'paper_type' => 'standard', 'binding' => 'paperback', 'color_print' => false],
                'owns_digital' => false,
                'status' => 'shipped',
                'tracking_code' => 'POST-845120977',
                'created_at' => now()->subDays(9),
            ],
        ];

        foreach ($plans as $plan) {
            $house = PrintHouse::query()->where('slug', $plan['house'])->first();
            $product = Product::query()->where('slug', $plan['product'])->first();

            if (! $house || ! $product) {
                continue;
            }

            $quote = app(PrintPricingService::class)->quote($house, $plan['options'], $plan['owns_digital']);

            DB::table('print_orders')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'print_house_id' => $house->id,
                    'paper_type' => $plan['options']['paper_type'],
                    'binding' => $plan['options']['binding'],
                ],
                [
                    'product_title' => $product->title,
                    'print_house_name' => $house->name,
                    'quantity' => $plan['options']['quantity'],
                    'color_print' => (bool) $plan['options']['color_print'],
                    'shipping_name' => $user->name,
                    'shipping_phone' => $user->phone ?? '09123334444',
                    'shipping_city' => 'تهران',
                    'shipping_address' => 'تهران، خیابان آزادی، پلاک ۱۲',
                    'postal_code' => '1418754512',
                    'notes' => null,
                    'unit_price' => $quote['unit_price'],
                    'delivery_fee' => $quote['delivery_fee'],
                    'discount_amount' => $quote['discount_amount'],
                    'total_price' => $quote['total_price'],
                    'digital_owner' => $plan['owns_digital'],
                    'delivery_days' => $quote['delivery_days'],
                    'tracking_code' => $plan['tracking_code'],
                    'status' => $plan['status'],
                    'cancelled_at' => null,
                    'created_at' => $plan['created_at'],
                    'updated_at' => $plan['created_at'],
                ],
            );
        }
    }
}

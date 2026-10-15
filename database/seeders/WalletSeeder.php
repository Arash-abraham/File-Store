<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', mb_strtolower(env('DEMO_USER_EMAIL', 'user@example.com')))->value('id');
        if (!$userId) {
            return;
        }

        $now = now();
        DB::table('wallets')->updateOrInsert(['user_id' => $userId], [
            'balance' => 1250000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $walletId = DB::table('wallets')->where('user_id', $userId)->value('id');

        DB::table('wallet_transactions')->updateOrInsert(
            ['wallet_id' => $walletId, 'description' => 'اعتبار اولیه‌ی کیف پول نمایشی'],
            [
                'type' => 'deposit',
                'amount' => 1250000,
                'status' => 'completed',
                'meta' => json_encode(['seed' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }
}

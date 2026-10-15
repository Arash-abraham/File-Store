<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WalletPaymentService
{
    public function createPaymentRequest(Wallet $wallet, int $amount, ?string $description = null): array
    {
        if ($amount < 10000) {
            return ['success' => false, 'message' => 'حداقل مبلغ شارژ ۱۰٬۰۰۰ تومان است.'];
        }

        $merchantId = config('services.zarinpal.merchant_id');
        if (!$merchantId) {
            return ['success' => false, 'message' => 'شناسه‌ی پذیرنده‌ی درگاه تنظیم نشده است.'];
        }

        $transaction = $wallet->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => $description ?? 'افزایش موجودی کیف پول',
            'status' => 'pending',
        ]);

        $sandbox = (bool) config('services.zarinpal.sandbox');
        $url = $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
            : 'https://api.zarinpal.com/pg/v4/payment/request.json';

        try {
            $response = Http::timeout(20)->post($url, [
                'merchant_id' => $merchantId,
                'amount' => $amount * 10,
                'currency' => 'IRR',
                'description' => $description ?? 'شارژ کیف پول فایل‌استور',
                'callback_url' => route('api.v1.wallet.zarinpal.callback'),
                'metadata' => ['wallet_transaction_id' => $transaction->id, 'user_id' => $wallet->user_id],
            ]);
            $result = $response->json() ?? [];

            if ($response->successful() && (int) data_get($result, 'data.code') === 100) {
                $authority = (string) data_get($result, 'data.authority');
                $transaction->update(['authority' => $authority]);

                return [
                    'success' => true,
                    'authority' => $authority,
                    'transaction_id' => $transaction->id,
                    'payment_url' => ($sandbox ? 'https://sandbox.zarinpal.com' : 'https://www.zarinpal.com').'/pg/StartPay/'.$authority,
                ];
            }

            $transaction->update(['status' => 'failed']);
            Log::warning('Zarinpal wallet request rejected', ['transaction_id' => $transaction->id, 'response' => $result]);
            return ['success' => false, 'message' => data_get($result, 'errors.message', 'ایجاد درخواست پرداخت ناموفق بود.')];
        } catch (\Throwable $exception) {
            $transaction->update(['status' => 'failed']);
            Log::error('Zarinpal wallet request failed', ['transaction_id' => $transaction->id, 'message' => $exception->getMessage()]);
            return ['success' => false, 'message' => 'ارتباط با درگاه پرداخت برقرار نشد.'];
        }
    }

    public function verifyPayment(string $authority): array
    {
        $transaction = WalletTransaction::query()->where('authority', $authority)->first();
        if (!$transaction) {
            return ['success' => false, 'message' => 'تراکنش کیف پول پیدا نشد.'];
        }
        if ($transaction->status === 'completed') {
            return ['success' => true, 'transaction' => $transaction, 'ref_id' => $transaction->ref_id, 'message' => 'این شارژ قبلاً ثبت شده است.'];
        }
        if ($transaction->status !== 'pending') {
            return ['success' => false, 'message' => 'وضعیت این تراکنش قابل تأیید نیست.'];
        }

        $merchantId = config('services.zarinpal.merchant_id');
        if (!$merchantId) {
            return ['success' => false, 'message' => 'شناسه‌ی پذیرنده‌ی درگاه تنظیم نشده است.'];
        }

        $url = config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
            : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

        try {
            $response = Http::timeout(20)->post($url, [
                'merchant_id' => $merchantId,
                'amount' => (int) $transaction->amount * 10,
                'authority' => $authority,
            ]);
            $result = $response->json() ?? [];
            $code = (int) data_get($result, 'data.code');

            if (!$response->successful() || !in_array($code, [100, 101], true)) {
                $transaction->update(['status' => 'failed']);
                return ['success' => false, 'message' => data_get($result, 'errors.message', 'تأیید شارژ کیف پول ناموفق بود.')];
            }

            $refId = (string) data_get($result, 'data.ref_id', '');
            $completed = DB::transaction(function () use ($transaction, $refId) {
                $locked = WalletTransaction::query()->lockForUpdate()->findOrFail($transaction->id);
                if ($locked->status === 'completed') {
                    return false;
                }
                if ($locked->status !== 'pending') {
                    return false;
                }

                $wallet = Wallet::query()->lockForUpdate()->findOrFail($locked->wallet_id);
                $locked->update(['status' => 'completed', 'ref_id' => $refId]);
                $wallet->increment('balance', (int) $locked->amount);

                return true;
            });

            return [
                'success' => true,
                'transaction' => $transaction->fresh(),
                'ref_id' => $refId,
                'message' => $completed ? 'کیف پول با موفقیت شارژ شد.' : 'این شارژ قبلاً ثبت شده است.',
            ];
        } catch (\Throwable $exception) {
            Log::error('Zarinpal wallet verification failed', ['authority' => $authority, 'message' => $exception->getMessage()]);
            return ['success' => false, 'message' => 'ارتباط با درگاه برای تأیید شارژ برقرار نشد.'];
        }
    }
}

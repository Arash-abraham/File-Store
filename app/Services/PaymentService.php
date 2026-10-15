<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function createPaymentRequest(Order $order, int $amount): array
    {
        if ($amount < 100) {
            return ['success' => false, 'message' => 'مبلغ پرداختی باید حداقل ۱۰۰ تومان باشد.'];
        }

        $merchantId = config('services.zarinpal.merchant_id');
        if (!$merchantId) {
            return ['success' => false, 'message' => 'شناسه‌ی پذیرنده‌ی درگاه تنظیم نشده است.'];
        }

        $sandbox = (bool) config('services.zarinpal.sandbox');
        $baseUrl = $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
            : 'https://api.zarinpal.com/pg/v4/payment/request.json';

        try {
            $response = Http::timeout(20)->post($baseUrl, [
                'merchant_id' => $merchantId,
                'amount' => $amount * 10,
                'currency' => 'IRR',
                'description' => 'پرداخت سفارش شماره '.$order->id,
                'callback_url' => route('api.v1.payments.zarinpal.callback'),
                'metadata' => ['order_id' => $order->id, 'user_id' => $order->user_id],
            ]);

            $result = $response->json() ?? [];
            if ($response->successful() && (int) data_get($result, 'data.code') === 100) {
                $authority = (string) data_get($result, 'data.authority');
                return [
                    'success' => true,
                    'authority' => $authority,
                    'payment_url' => ($sandbox ? 'https://sandbox.zarinpal.com' : 'https://www.zarinpal.com').'/pg/StartPay/'.$authority,
                ];
            }

            Log::warning('Zarinpal payment request rejected', [
                'order_id' => $order->id,
                'amount' => $amount,
                'response' => $result,
            ]);

            return [
                'success' => false,
                'message' => data_get($result, 'errors.message', 'ایجاد درخواست پرداخت در درگاه ناموفق بود.'),
            ];
        } catch (\Throwable $exception) {
            Log::error('Zarinpal payment request failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
            return ['success' => false, 'message' => 'ارتباط با درگاه پرداخت برقرار نشد.'];
        }
    }

    public function verifyPayment(Order $order, string $authority): array
    {
        $amount = (int) ($order->remaining_amount ?: $order->final_amount);
        if ($amount < 100) {
            return ['success' => false, 'message' => 'مبلغ پرداختی معتبر نیست.'];
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
                'amount' => $amount * 10,
                'authority' => $authority,
            ]);
            $result = $response->json() ?? [];

            if ($response->successful() && in_array((int) data_get($result, 'data.code'), [100, 101], true)) {
                return [
                    'success' => true,
                    'ref_id' => (string) data_get($result, 'data.ref_id', ''),
                    'code' => (string) data_get($result, 'data.code'),
                    'message' => 'پرداخت با موفقیت تأیید شد.',
                ];
            }

            Log::warning('Zarinpal payment verification rejected', [
                'order_id' => $order->id,
                'authority' => $authority,
                'response' => $result,
            ]);

            return ['success' => false, 'message' => data_get($result, 'errors.message', 'تأیید پرداخت ناموفق بود.')];
        } catch (\Throwable $exception) {
            Log::error('Zarinpal verification failed', [
                'order_id' => $order->id,
                'authority' => $authority,
                'message' => $exception->getMessage(),
            ]);
            return ['success' => false, 'message' => 'ارتباط با درگاه برای تأیید پرداخت برقرار نشد.'];
        }
    }
}

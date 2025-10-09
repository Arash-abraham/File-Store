<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function createPaymentRequest(Order $order): array
    {
        try {
            $amount = $order->final_amount * 10; // تبدیل تومن به ریال

            $merchantId = env('ZARINPAL_MERCHANT_ID', '00000000-0000-0000-0000-000000000000');
            $isSandbox = filter_var(env('ZARINPAL_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);

            // درخواست پرداخت از زرین‌پال
            $response = zarinpal()
                ->merchantId($merchantId)
                ->amount($amount)
                ->request()
                ->description('سفارش شماره ' . $order->id)
                ->callbackUrl(route('api.payment.callback', [], false)) // URL کامل callback
                ->mobile($order->user?->phone ?? '09123456789')
                ->email($order->user?->email ?? 'test@example.com')
                ->send();

            if (!$response->success()) {
                throw new \Exception('خطا در ایجاد درخواست پرداخت: ' . $response->error()->message());
            }

            // ذخیره Authority
            $order->update(['reference' => $response->authority()]);

            // ساخت URL پرداخت
            $paymentUrl = $isSandbox
                ? 'https://sandbox.zarinpal.com/pg/StartPay/' . $response->authority()
                : 'https://www.zarinpal.com/pg/StartPay/' . $response->authority();

            Log::info('درخواست پرداخت موفق', ['authority' => $response->authority(), 'sandbox' => $isSandbox]);

            return [
                'success' => true,
                'authority' => $response->authority(),
                'payment_url' => $paymentUrl,
            ];

        } catch (\Exception $e) {
            Log::error('خطا در ایجاد پرداخت', ['error' => $e->getMessage(), 'order_id' => $order->id]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function verifyPayment(Order $order, string $authority, string $status = 'OK'): array
    {
        try {
            if ($status !== 'OK' || $order->reference !== $authority) {
                throw new \Exception('شناسه تراکنش نامعتبر');
            }

            $amount = $order->final_amount * 10;

            $merchantId = env('ZARINPAL_MERCHANT_ID', '24234b4b-c870-46c7-bfce-b4852cfb0875');
            $isSandbox = filter_var(env('ZARINPAL_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);

            $response = zarinpal()
                ->merchantId($merchantId)
                ->amount($amount)
                ->verification()
                ->authority($authority)
                ->send();

            if (!$response->success()) {
                throw new \Exception('پرداخت ناموفق: ' . $response->error()->message());
            }

            $order->update(['transaction_id' => $response->refId()]);

            Log::info('تأیید پرداخت موفق', ['ref_id' => $response->refId(), 'sandbox' => $isSandbox]);

            return [
                'success' => true,
                'ref_id' => $response->refId(),
                'message' => 'پرداخت با موفقیت تأیید شد',
            ];

        } catch (\Exception $e) {
            Log::error('خطا در تأیید پرداخت', ['error' => $e->getMessage(), 'order_id' => $order->id]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
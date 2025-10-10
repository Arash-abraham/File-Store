<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $merchantId;
    protected $callbackUrl;
    protected $isSandbox;

    public function __construct()
    {
        $this->merchantId = env('ZARINPAL_MERCHANT_ID');
        $this->callbackUrl = route('payment.verify');
        $this->isSandbox = env('ZARINPAL_SANDBOX', false);
    }

    public function createPaymentRequest($order)
    {
        try {
            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
                : 'https://api.zarinpal.com/pg/v4/payment/request.json';

            $response = Http::post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $order->final_amount * 10, // تبدیل تومان به ریال
                'currency' => 'IRR',
                'description' => 'پرداخت سفارش شماره ' . $order->id,
                'callback_url' => $this->callbackUrl,
            ]);

            $result = $response->json();

            Log::info('Zarinpal Request Response:', $result);

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                return [
                    'success' => true,
                    'payment_url' => $this->isSandbox
                        ? 'https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']['authority']
                        : 'https://www.zarinpal.com/pg/StartPay/' . $result['data']['authority'],
                    'authority' => $result['data']['authority'],
                ];
            }

            return [
                'success' => false,
                'message' => $result['errors']['message'] ?? 'خطا در ایجاد درخواست پرداخت',
            ];
        } catch (\Exception $e) {
            Log::error('Zarinpal Error:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با درگاه: ' . $e->getMessage(),
            ];
        }
    }

    public function verifyPayment($order, $authority, $status)
    {
        try {
            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
                : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

            $response = Http::post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $order->final_amount * 10, // تبدیل تومان به ریال
                'authority' => $authority,
            ]);

            $result = $response->json();

            Log::info('Zarinpal Verify Response:', $result);

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                return [
                    'success' => true,
                    'ref_id' => $result['data']['ref_id'],
                    'message' => 'پرداخت با موفقیت تأیید شد',
                ];
            }

            return [
                'success' => false,
                'message' => $result['errors']['message'] ?? 'خطا در تأیید پرداخت',
            ];
        } catch (\Exception $e) {
            Log::error('Zarinpal Verify Error:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با درگاه: ' . $e->getMessage(),
            ];
        }
    }
}
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

    public function createPaymentRequest($order, $amount)
    {
        try {
            if ($amount < 100) {
                Log::warning('Payment amount too low', [
                    'order_id' => $order->id,
                    'amount' => $amount
                ]);
                return [
                    'success' => false,
                    'message' => 'مبلغ پرداختی باید حداقل 100 تومان باشد'
                ];
            }

            $amountInRial = (int)($amount * 10); 
            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
                : 'https://api.zarinpal.com/pg/v4/payment/request.json';

            $response = Http::post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $amountInRial,
                'currency' => 'IRR',
                'description' => 'پرداخت سفارش شماره ' . $order->id,
                'callback_url' => $this->callbackUrl,
            ]);

            $result = $response->json();

            Log::info('Zarinpal Request Response:', [
                'order_id' => $order->id,
                'amount' => $amount,
                'amount_in_rial' => $amountInRial,
                'response' => $result
            ]);

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                return [
                    'success' => true,
                    'payment_url' => $this->isSandbox
                        ? 'https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']['authority']
                        : 'https://www.zarinpal.com/pg/StartPay/' . $result['data']['authority'],
                    'authority' => $result['data']['authority'],
                ];
            }

            Log::warning('Zarinpal request failed', [
                'order_id' => $order->id,
                'amount' => $amount,
                'errors' => $result['errors'] ?? 'No errors provided'
            ]);

            return [
                'success' => false,
                'message' => $result['errors']['message'] ?? 'خطا در ایجاد درخواست پرداخت',
            ];
        } catch (\Exception $e) {
            Log::error('Zarinpal Request Error:', [
                'order_id' => $order->id,
                'amount' => $amount,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با درگاه: ' . $e->getMessage(),
            ];
        }
    }

    public function verifyPayment($order, $authority)
    {
        try {
            if ($order->remaining_amount < 100) {
                Log::warning('Verification amount too low', [
                    'order_id' => $order->id,
                    'amount' => $order->remaining_amount
                ]);
                return [
                    'success' => false,
                    'message' => 'مبلغ پرداختی باید حداقل 100 تومان باشد'
                ];
            }

            $amountInRial = (int)($order->remaining_amount * 10); 
            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
                : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

            $response = Http::post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $amountInRial,
                'authority' => $authority,
            ]);

            $result = $response->json();

            Log::info('Zarinpal Verify Response:', [
                'order_id' => $order->id,
                'amount' => $order->remaining_amount,
                'amount_in_rial' => $amountInRial,
                'authority' => $authority,
                'response' => $result
            ]);

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                return [
                    'success' => true,
                    'ref_id' => $result['data']['ref_id'],
                    'message' => 'پرداخت با موفقیت تأیید شد',
                ];
            }

            Log::warning('Zarinpal verification failed', [
                'order_id' => $order->id,
                'amount' => $order->remaining_amount,
                'amount_in_rial' => $amountInRial,
                'authority' => $authority,
                'errors' => $result['errors'] ?? 'No errors provided'
            ]);

            return [
                'success' => false,
                'message' => $result['errors']['message'] ?? 'خطا در تأیید پرداخت',
            ];
        } catch (\Exception $e) {
            Log::error('Zarinpal Verify Error:', [
                'order_id' => $order->id,
                'amount' => $order->remaining_amount,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با درگاه: ' . $e->getMessage(),
            ];
        }
    }
}
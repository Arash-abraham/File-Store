<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WalletPaymentService
{
    protected $merchantId;
    protected $callbackUrl;
    protected $isSandbox;

    public function __construct()
    {
        $this->merchantId = env('ZARINPAL_MERCHANT_ID');
        $this->callbackUrl = route('wallet.payment.verify');
        $this->isSandbox = env('ZARINPAL_SANDBOX', false);
    }

    public function createPaymentRequest(Wallet $wallet, $amount, $description = null)
    {
        try {
            DB::beginTransaction();

            $transaction = $wallet->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'description' => $description ?? 'افزایش موجودی کیف پول',
                'status' => 'pending',
            ]);

            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
                : 'https://api.zarinpal.com/pg/v4/payment/request.json';

            $response = Http::timeout(30)->post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $amount * 10, 
                'currency' => 'IRR',
                'description' => 'افزایش موجودی کیف پول - ' . number_format($amount) . ' تومان',
                'callback_url' => $this->callbackUrl,
                'metadata' => [
                    'wallet_transaction_id' => $transaction->id,
                    'user_id' => $wallet->user_id,
                ],
            ]);

            $result = $response->json();

            Log::info('Wallet Payment Request:', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'response' => $result
            ]);

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                $transaction->update([
                    'authority' => $result['data']['authority']
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'payment_url' => $this->isSandbox
                        ? 'https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']['authority']
                        : 'https://www.zarinpal.com/pg/StartPay/' . $result['data']['authority'],
                    'authority' => $result['data']['authority'],
                    'transaction_id' => $transaction->id,
                ];
            }

            DB::rollBack();
            
            $errorMessage = $result['errors']['message'] ?? 'خطا در ایجاد درخواست پرداخت';
            return [
                'success' => false,
                'message' => $errorMessage,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Payment Request Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با درگاه پرداخت',
            ];
        }
    }

    public function verifyPayment($authority)
    {
        try {
            $transaction = WalletTransaction::where('authority', $authority)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                return [
                    'success' => false,
                    'message' => 'تراکنش یافت نشد',
                ];
            }

            $baseUrl = $this->isSandbox
                ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
                : 'https://api.zarinpal.com/pg/v4/payment/verify.json';

            $response = Http::timeout(30)->post($baseUrl, [
                'merchant_id' => $this->merchantId,
                'amount' => $transaction->amount * 10, 
                'authority' => $authority,
            ]);

            $result = $response->json();

            Log::info('Wallet Payment Verify:', [
                'authority' => $authority,
                'response' => $result
            ]);

            DB::beginTransaction();

            if ($response->successful() && isset($result['data']['code']) && $result['data']['code'] == 100) {
                $transaction->markAsCompleted($result['data']['ref_id']);
                
                $transaction->wallet->increment('balance', $transaction->amount);

                DB::commit();

                return [
                    'success' => true,
                    'transaction' => $transaction,
                    'ref_id' => $result['data']['ref_id'],
                    'message' => 'پرداخت با موفقیت انجام شد و موجودی کیف پول افزایش یافت',
                ];
            }

            $transaction->markAsFailed();
            DB::commit();

            $errorMessage = $result['errors']['message'] ?? 'پرداخت ناموفق بود';
            return [
                'success' => false,
                'message' => $errorMessage,
                'transaction' => $transaction,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Payment Verify Error:', [
                'authority' => $authority,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'خطا در تأیید پرداخت',
            ];
        }
    }
}
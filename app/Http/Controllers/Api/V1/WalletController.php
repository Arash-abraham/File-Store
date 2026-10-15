<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Services\WalletPaymentService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $wallet = Wallet::query()->firstOrCreate(['user_id' => $request->user()->id], ['balance' => 0]);

        return response()->json([
            'data' => [
                'balance' => (int) $wallet->balance,
                'transactions' => $wallet->transactions()->latest()->paginate(15)->through(fn ($transaction) => [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => (int) $transaction->amount,
                    'description' => $transaction->description,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at?->toISOString(),
                ]),
            ],
        ]);
    }

    public function deposit(Request $request, WalletPaymentService $paymentService)
    {
        $data = $request->validate(['amount' => ['required', 'integer', 'min:10000', 'max:100000000']]);
        $wallet = Wallet::query()->firstOrCreate(['user_id' => $request->user()->id], ['balance' => 0]);

        if (empty(config('services.zarinpal.merchant_id'))) {
            return response()->json(['message' => 'درگاه پرداخت پیکربندی نشده است.'], 503);
        }

        $result = $paymentService->createPaymentRequest($wallet, $data['amount'], 'شارژ کیف پول فایل‌استور');
        if (!$result['success']) {
            return response()->json(['message' => $result['message'] ?? 'ایجاد درخواست پرداخت ناموفق بود.'], 502);
        }

        return response()->json([
            'message' => 'برای تکمیل شارژ به درگاه پرداخت هدایت شوید.',
            'data' => [
                'payment_url' => $result['payment_url'],
                'transaction_id' => $result['transaction_id'],
            ],
        ], 201);
    }

    public function zarinpalCallback(Request $request, WalletPaymentService $paymentService)
    {
        $authority = $request->query('Authority', $request->query('authority'));
        $status = $request->query('Status', $request->query('status'));
        $frontend = config('services.frontend.url');

        if (!$authority || $status !== 'OK') {
            return redirect($frontend.'/account/wallet?status=cancelled');
        }

        $result = $paymentService->verifyPayment($authority);
        if (!$result['success']) {
            return redirect($frontend.'/account/wallet?status=failed');
        }

        return redirect($frontend.'/account/wallet?status=success&ref='.urlencode((string) $result['ref_id']));
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\PaymentSuccessfulNotification;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    public function zarinpalCallback(Request $request, PaymentService $paymentService)
    {
        $authority = $request->query('Authority', $request->query('authority'));
        $status = $request->query('Status', $request->query('status'));
        $frontend = config('services.frontend.url');

        $order = $authority
            ? Order::query()->where('payment_authority', $authority)->with('user')->first()
            : null;

        if (!$order) {
            return redirect($frontend.'/checkout/result?status=failed&reason=order-not-found');
        }

        if ($order->status === 'paid') {
            return redirect($frontend.'/checkout/result?status=success&order_id='.$order->id);
        }

        if ($status !== 'OK') {
            $order->update(['status' => 'failed']);
            $order->payments()->where('status', 'pending')->update(['status' => 'failed']);

            return redirect($frontend.'/checkout/result?status=cancelled&order_id='.$order->id);
        }

        $verification = $paymentService->verifyPayment($order, $authority);
        if (!$verification['success']) {
            $order->update(['status' => 'failed']);
            $order->payments()->where('status', 'pending')->update(['status' => 'failed']);

            return redirect($frontend.'/checkout/result?status=failed&order_id='.$order->id);
        }

        $wasPaid = false;
        DB::transaction(function () use ($order, $verification, $authority, &$wasPaid) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($lockedOrder->status === 'paid') {
                return;
            }

            $lockedOrder->markAsPaid('zarinpal', (string) $verification['ref_id']);
            $payment = $lockedOrder->payments()->where('gateway', 'zarinpal')->latest('id')->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => (string) $verification['ref_id'],
                    'external_ref' => $authority,
                    'paid_at' => now(),
                    'response_code' => '100',
                ]);
            }
            $wasPaid = true;
        });

        if ($wasPaid && $order->user) {
            try {
                $order->user->notify(new PaymentSuccessfulNotification([
                    'amount' => $order->final_amount,
                    'order_id' => $order->id,
                    'authority' => $authority,
                    'description' => 'پرداخت سفارش شماره '.$order->id,
                ]));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect($frontend.'/checkout/result?status=success&order_id='.$order->id.'&ref='.urlencode((string) $verification['ref_id']));
    }
}

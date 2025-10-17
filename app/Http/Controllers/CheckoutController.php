<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $paymentService;

    public function __construct(CartService $cartService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->paymentService = $paymentService;
    }

    public function showCheckout()
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = collect();
        $total = 0;
        $discount = 0;

        if ($cart) {
            $cartItems = $cart->items()->with('product')->get();
            $total = $cart->total;
            $discount = $cart->discount ?? 0;
        }

        return view('app.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'discount' => $discount
        ]);
    }

    public function processCheckout(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string',
            'payment_method' => 'sometimes|string|in:zarinpal',
        ]);

        $cart = $this->cartService->getCart(auth()->id(), $request->session_token);

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'سبد خرید خالی است'
            ], 400);
        }

        try {
            $discountAmount = $cart->discount ?? 0;
            $finalAmount = max(0, $cart->total - $discountAmount);
            $wallet = auth()->user()->wallet;
            $walletBalance = $wallet ? $wallet->balance : 0;
            $walletCoverage = min($walletBalance, $finalAmount);
            $gatewayAmount = $finalAmount - $walletCoverage;

            Log::info('Checkout process started', [
                'cart_id' => $cart->id,
                'cart_total' => $cart->total,
                'discount' => $discountAmount,
                'final_amount' => $finalAmount,
                'wallet_balance' => $walletBalance,
                'wallet_coverage' => $walletCoverage,
                'gateway_amount' => $gatewayAmount,
                'coupon_code' => $cart->coupon_code
            ]);

            $orderData = [
                'discount_amount' => $discountAmount,
                'coupon_code' => $cart->coupon_code,
                'paid_from_wallet' => $walletCoverage,
                'remaining_amount' => $gatewayAmount,
                'final_amount' => $finalAmount,
                'payment_method' => $gatewayAmount > 0 ? ($request->payment_method ?? 'zarinpal') : null,
            ];

            Log::info('Order data before creation', [
                'cart_id' => $cart->id,
                'order_data' => $orderData
            ]);

            if ($gatewayAmount <= 0) {
                $orderData['status'] = 'paid';
                $order = $this->cartService->convertToOrder($cart, $orderData);

                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'gateway' => 'wallet',
                    'gateway_name' => 'کیف پول',
                    'amount' => $finalAmount,
                    'status' => 'completed',
                    'transaction_id' => 'WALLET-' . $order->id,
                    'external_ref' => null,
                    'verified_at' => now(),
                ]);

                session()->forget('discount');

                return response()->json([
                    'success' => true,
                    'message' => 'پرداخت با موفقیت از کیف پول انجام شد',
                    'data' => [
                        'order_id' => $order->id,
                        'payment_url' => null,
                        'redirect_url' => route('payment.success', ['order_id' => $order->id]),
                    ]
                ], 201);
            }

            if ($gatewayAmount < 100) {
                if ($orderData['paid_from_wallet'] > 0) {
                    $this->cartService->refundToWallet(auth()->id(), $orderData['paid_from_wallet'], 0);
                }
                Log::warning('Gateway amount too low', [
                    'cart_id' => $cart->id,
                    'gateway_amount' => $gatewayAmount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'مبلغ پرداختی باید حداقل 100 تومان باشد'
                ], 400);
            }

            $orderData['status'] = 'pending';
            $order = $this->cartService->convertToOrder($cart, $orderData);

            Log::info('Order created', [
                'order_id' => $order->id,
                'remaining_amount' => $order->remaining_amount,
                'paid_from_wallet' => $order->paid_from_wallet,
                'final_amount' => $order->final_amount
            ]);

            $paymentResponse = $this->paymentService->createPaymentRequest($order, $gatewayAmount);

            if (!$paymentResponse['success']) {
                if ($order->paid_from_wallet > 0) {
                    $this->cartService->refundToWallet($order->user_id, $order->paid_from_wallet, $order->id);
                }
                $order->update(['status' => 'failed']);
                Log::warning('Payment request failed', [
                    'order_id' => $order->id,
                    'message' => $paymentResponse['message']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $paymentResponse['message'] ?? 'خطا در ایجاد درخواست پرداخت'
                ], 400);
            }

            $order->update(['payment_authority' => $paymentResponse['authority']]);

            Log::info('Payment request created', [
                'order_id' => $order->id,
                'authority' => $paymentResponse['authority'],
                'payment_url' => $paymentResponse['payment_url']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'سفارش با موفقیت ایجاد شد',
                'data' => [
                    'order_id' => $order->id,
                    'payment_url' => $paymentResponse['payment_url'],
                    'authority' => $paymentResponse['authority']
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Checkout process error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'session_token' => $request->session_token,
                'cart_id' => $cart->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد سفارش: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verify(Request $request): Response|RedirectResponse
    {
        $order = null;
        $authority = null;
        $status = null;

        try {
            if ($request->method() === 'GET') {
                $authority = $request->query('Authority') ?? $request->query('authority');
                $status = $request->query('Status') ?? $request->query('status');
            } elseif ($request->method() === 'POST') {
                $authority = $request->input('Authority') ?? $request->input('authority');
                $status = $request->input('Status') ?? $request->input('status');
            } else {
                return redirect()->route('payment.failed')
                    ->with('error', 'متد درخواست پشتیبانی نمی‌شود');
            }

            if (!$authority || !$status) {
                return redirect()->route('payment.failed')
                    ->with('error', 'پارامترهای ضروری پرداخت یافت نشد');
            }

            $order = Order::where('payment_authority', $authority)->first();

            if (!$order) {
                return redirect()->route('payment.failed')
                    ->with('error', 'سفارش مرتبط با کد پرداخت یافت نشد');
            }

            Log::info('Order before verification', [
                'order_id' => $order->id,
                'remaining_amount' => $order->remaining_amount,
                'paid_from_wallet' => $order->paid_from_wallet,
                'final_amount' => $order->final_amount,
                'status' => $order->status
            ]);

            if ($order->status === 'paid') {
                return redirect()->route('payment.success', ['order_id' => $order->id])
                    ->with('info', 'این سفارش قبلاً پرداخت شده است');
            }

            if ($status !== 'OK') {
                $order->update(['status' => 'failed']);
                if ($order->paid_from_wallet > 0) {
                    $this->cartService->refundToWallet($order->user_id, $order->paid_from_wallet, $order->id);
                }
                return redirect()->route('payment.failed')
                    ->with('error', 'پرداخت توسط کاربر لغو شد');
            }

            $paymentResponse = $this->paymentService->verifyPayment($order, $authority);

            if (!$paymentResponse['success']) {
                $order->update(['status' => 'failed']);
                if ($order->paid_from_wallet > 0) {
                    $this->cartService->refundToWallet($order->user_id, $order->paid_from_wallet, $order->id);
                }
                Log::warning('Payment verification failed', [
                    'order_id' => $order->id,
                    'message' => $paymentResponse['message']
                ]);
                return redirect()->route('payment.failed')
                    ->with('error', $paymentResponse['message'] ?? 'خطا در تأیید پرداخت');
            }

            $order->markAsPaid('zarinpal', $paymentResponse['ref_id']);

            Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'gateway' => 'zarinpal',
                'gateway_name' => 'زرین‌پال',
                'amount' => $order->remaining_amount,
                'status' => 'completed',
                'transaction_id' => $paymentResponse['ref_id'],
                'external_ref' => $authority,
                'verified_at' => now(),
            ]);

            session()->forget('discount');

            return redirect()->route('payment.success', ['order_id' => $order->id])
                ->with('success', 'پرداخت با موفقیت انجام شد! شماره پیگیری: ' . $paymentResponse['ref_id']);

        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage(), [
                'authority' => $authority ?? 'unknown',
                'status' => $status ?? 'unknown',
                'order_id' => $order ? $order->id : 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            if ($order && $order->paid_from_wallet > 0) {
                $this->cartService->refundToWallet($order->user_id, $order->paid_from_wallet, $order->id);
            }

            return redirect()->route('payment.failed')
                ->with('error', 'خطای سیستمی در تأیید پرداخت: ' . $e->getMessage());
        }
    }
}
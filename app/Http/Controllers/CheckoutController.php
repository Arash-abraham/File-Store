<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\PaymentService;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService
    ) {}

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'sometimes|string',
            'coupon_code' => 'sometimes|string',
            'payment_gateway' => 'sometimes|string|in:zarinpal',
        ]);

        $user = $request->user();
        $cart = $this->cartService->getCart($user?->id, $request->header('session-token'));

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'سبد خرید خالی است'
            ], 400);
        }

        try {
            $order = $this->cartService->convertToOrder($cart, [
                'payment_gateway' => $request->payment_gateway ?? 'zarinpal',
            ]);

            $paymentData = $this->paymentService->createPaymentRequest($order);

            if (!$paymentData['success']) {
                throw new \Exception($paymentData['message']);
            }

            return response()->json([
                'success' => true,
                'message' => 'سفارش با موفقیت ایجاد شد',
                'data' => [
                    'order_id' => $order->id,
                    'payment_url' => $paymentData['payment_url'],
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد سفارش: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'authority' => 'required|string',
            'status' => 'required|in:OK,NOK',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            $verification = $this->paymentService->verifyPayment($order, $request->authority, $request->status);

            if ($verification['success']) {
                $order->markAsPaid('zarinpal', $request->authority);

                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'gateway' => 'zarinpal',
                    'gateway_name' => 'zarinpal',
                    'amount' => $order->final_amount * 10,
                    'status' => 'completed',
                    'transaction_id' => $request->authority,
                    'external_ref' => $verification['ref_id'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'پرداخت با موفقیت تأیید شد',
                    'data' => $order->fresh()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'پرداخت ناموفق بود: ' . ($verification['message'] ?? '')
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تأیید پرداخت: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showCart(Request $request)
    {
        $user = $request->user();
        $cart = $this->cartService->getCart($user?->id, $request->header('session-token'));

        if (!$cart) {
            return view('cart.checkout', ['cartItems' => collect(), 'total' => 0, 'discount' => 0]);
        }

        $total = $cart->total;
        $discount = 0; // می‌تونی بعداً از کوپن واقعی استفاده کنی

        return view('cart.checkout', [
            'cartItems' => $cart->items,
            'total' => $total,
            'discount' => $discount
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string',
            'payment_gateway' => 'sometimes|string|in:zarinpal',
        ]);

        $sessionToken = $request->session_token;
        $cart = $this->getCart(null, $sessionToken);

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'سبد خرید خالی است'
            ], 400);
        }

        try {
            $order = $this->convertToOrder($cart, [
                'payment_gateway' => $request->payment_gateway ?? 'zarinpal',
            ]);

            // شبیه‌سازی درگاه پرداخت
            $authority = 'test_auth_' . time();
            $order->update(['reference' => $authority]);
            
            $paymentUrl = "https://sandbox.zarinpal.com/pg/StartPay/{$authority}";

            return response()->json([
                'success' => true,
                'message' => 'سفارش با موفقیت ایجاد شد',
                'data' => [
                    'order_id' => $order->id,
                    'payment_url' => $paymentUrl,
                    'authority' => $authority
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
            
            if ($request->status !== 'OK' || $order->reference !== $request->authority) {
                throw new \Exception('شناسه تراکنش نامعتبر');
            }

            // شبیه‌سازی پرداخت موفق
            $refId = 'test_ref_' . time();
            
            $order->update([
                'status' => 'paid',
                'transaction_id' => $refId,
                'paid_at' => now()
            ]);

            Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'gateway' => 'zarinpal',
                'amount' => $order->final_amount * 10,
                'status' => 'completed',
                'transaction_id' => $refId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'پرداخت با موفقیت تأیید شد',
                'data' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تأیید پرداخت: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== متدهای مستقیم دیتابیس ==========

    private function getCart(?int $userId = null, ?string $sessionToken = null): ?Cart
    {
        if ($sessionToken) {
            return Cart::where('session_token', $sessionToken)
                ->where('status', 'active')
                ->first();
        }
        return null;
    }

    private function convertToOrder(Cart $cart, array $orderData = []): Order
    {
        return DB::transaction(function () use ($cart, $orderData) {
            // محاسبه مجموع
            $total = $cart->items->sum('subtotal');
            
            $order = Order::create(array_merge([
                'user_id' => $cart->user_id,
                'total_amount' => $total,
                'status' => 'pending',
            ], $orderData));

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_title' => $cartItem->product->title,
                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->subtotal
                ]);
            }

            $cart->update(['status' => 'converted']);

            return $order;
        });
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request, PaymentService $paymentService)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:zarinpal,wallet'],
        ]);

        $user = $request->user();
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with('items.product')
            ->latest('id')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'سبد خرید خالی است.'], 422);
        }

        foreach ($cart->items as $item) {
            if (!$item->product || !$item->product->isAvailable()) {
                return response()->json(['message' => 'یکی از محصولات سبد خرید دیگر در دسترس نیست.'], 422);
            }
        }

        $productIds = $cart->items->pluck('product_id')->all();
        $alreadyOwned = $user->paidOrderItems()
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->unique()
            ->values();

        if ($alreadyOwned->isNotEmpty()) {
            // Free the cart from books the reader already owns, so the next attempt
            // only contains what is actually left to buy.
            $ownedTitles = $cart->items
                ->whereIn('product_id', $alreadyOwned)
                ->map(fn ($item) => $item->product?->title ?? 'کتاب')
                ->values();

            $cart->items()->whereIn('product_id', $alreadyOwned)->delete();

            if ($cart->items()->count() === 0) {
                $cart->update(['discount' => 0, 'coupon_code' => null]);
            } else {
                $remaining = (int) $cart->items()->with('product')->get()->sum(fn ($item) => (int) $item->product->final_price);
                $cart->update(['discount' => min((int) $cart->discount, $remaining)]);
            }

            return response()->json([
                'message' => 'این کتاب‌ها را قبلاً خریده‌ای و از سبد حذف شدند: '.$ownedTitles->implode('، '),
                'reason' => 'already_owned',
                'owned_product_ids' => $alreadyOwned,
                'owned_titles' => $ownedTitles,
            ], 422);
        }

        $subtotal = (int) $cart->items->sum(fn ($item) => (int) $item->product->final_price * (int) $item->quantity);
        $discount = min($subtotal, (int) ($cart->discount ?? 0));
        $amount = max(0, $subtotal - $discount);

        if ($amount < 1) {
            return response()->json(['message' => 'مبلغ سفارش معتبر نیست.'], 422);
        }

        if ($data['payment_method'] === 'wallet') {
            return $this->payWithWallet($cart, $user->id, $subtotal, $discount, $amount);
        }

        if (empty(config('services.zarinpal.merchant_id'))) {
            return response()->json(['message' => 'درگاه پرداخت در حال حاضر پیکربندی نشده است.'], 503);
        }

        $order = DB::transaction(function () use ($cart, $user, $subtotal, $discount, $amount) {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'session_token' => $cart->session_token,
                'status' => 'pending',
                'total_amount' => $subtotal,
                'discount_amount' => $discount,
                'coupon_id' => $cart->coupon_code
                    ? \App\Models\Coupon::query()->where('code', $cart->coupon_code)->value('id')
                    : null,
                'payment_method' => 'zarinpal',
                'payment_gateway' => 'zarinpal',
                'remaining_amount' => $amount,
                'paid_from_wallet' => 0,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = (int) $item->product->final_price;
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $unitPrice * (int) $item->quantity,
                ]);
            }

            Payment::query()->create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'gateway' => 'zarinpal',
                'gateway_name' => 'زرین‌پال',
                'amount' => $amount,
                'status' => 'pending',
                'transaction_id' => 'INIT-'.$order->id.'-'.Str::upper(Str::random(8)),
                'meta' => ['source' => 'storefront-api'],
            ]);

            return $order;
        });

        $payment = $paymentService->createPaymentRequest($order, $amount);
        if (!$payment['success']) {
            $order->update(['status' => 'failed']);
            $order->payments()->update(['status' => 'failed']);

            return response()->json(['message' => $payment['message'] ?? 'ایجاد درخواست پرداخت انجام نشد.'], 502);
        }

        $order->update(['payment_authority' => $payment['authority']]);
        $order->payments()->latest('id')->first()?->update(['external_ref' => $payment['authority']]);
        $cart->items()->delete();
        $cart->update(['status' => 'converted', 'discount' => 0, 'coupon_code' => null]);

        return response()->json([
            'message' => 'درخواست پرداخت آماده است.',
            'data' => [
                'order_id' => $order->id,
                'amount' => $amount,
                'payment_url' => $payment['payment_url'],
            ],
        ], 201);
    }

    private function payWithWallet(Cart $cart, int $userId, int $subtotal, int $discount, int $amount)
    {
        $result = DB::transaction(function () use ($cart, $userId, $subtotal, $discount, $amount) {
            $wallet = Wallet::query()->where('user_id', $userId)->lockForUpdate()->first();

            if (!$wallet || (int) $wallet->balance < $amount) {
                return null;
            }

            $order = Order::query()->create([
                'user_id' => $userId,
                'session_token' => $cart->session_token,
                'status' => 'paid',
                'total_amount' => $subtotal,
                'discount_amount' => $discount,
                'coupon_id' => $cart->coupon_code
                    ? \App\Models\Coupon::query()->where('code', $cart->coupon_code)->value('id')
                    : null,
                'payment_method' => 'wallet',
                'payment_gateway' => 'wallet',
                'transaction_id' => 'WALLET-'.Str::upper(Str::random(12)),
                'paid_at' => now(),
                'paid_from_wallet' => $amount,
                'remaining_amount' => 0,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = (int) $item->product->final_price;
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $unitPrice * (int) $item->quantity,
                ]);
            }

            $wallet->decrement('balance', $amount);
            WalletTransaction::query()->create([
                'wallet_id' => $wallet->id,
                'type' => 'purchase',
                'amount' => $amount,
                'description' => 'پرداخت سفارش شماره '.$order->id,
                'status' => 'completed',
                'meta' => ['order_id' => $order->id],
            ]);
            Payment::query()->create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'gateway' => 'wallet',
                'gateway_name' => 'کیف پول',
                'amount' => $amount,
                'status' => 'completed',
                'transaction_id' => $order->transaction_id,
                'paid_at' => now(),
                'meta' => ['source' => 'storefront-api'],
            ]);

            $cart->items()->delete();
            $cart->update(['status' => 'converted', 'discount' => 0, 'coupon_code' => null]);

            return $order;
        });

        if (!$result) {
            return response()->json(['message' => 'موجودی کیف پول برای پرداخت کافی نیست.'], 422);
        }

        return response()->json([
            'message' => 'پرداخت با کیف پول انجام شد.',
            'data' => ['order_id' => $result->id, 'amount' => $amount, 'status' => 'paid'],
        ], 201);
    }
}

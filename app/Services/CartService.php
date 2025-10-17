<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function addToCart(array $data): CartItem
    {
        return DB::transaction(function () use ($data) {
            $cart = $this->findOrCreateCart($data['user_id'], $data['session_token']);

            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'] ?? 1;

            if (!$product->availability) {
                throw new \Exception('محصول ناموجود است');
            }

            $existingItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $quantity,
                    'subtotal' => $product->original_price * ($existingItem->quantity + $quantity)
                ]);
                return $existingItem;
            }

            return CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->original_price,
                'subtotal' => $product->original_price * $quantity
            ]);
        });
    }

    public function updateQuantity(int $cartItemId, int $quantity): CartItem
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $product = $cartItem->product;

        if ($quantity < 1) {
            throw new \InvalidArgumentException('تعداد باید حداقل ۱ باشد');
        }

        if (!$product->availability) {
            throw new \Exception('محصول ناموجود است');
        }

        $cartItem->update([
            'quantity' => $quantity,
            'subtotal' => $cartItem->unit_price * $quantity
        ]);

        return $cartItem;
    }

    public function removeFromCart(int $cartItemId): bool
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartId = $cartItem->cart_id;
        $cart = Cart::findOrFail($cartId);

        $cartItem->delete();
        return  $cart->delete();
    }

    public function getCart(?int $userId = null, ?string $sessionToken = null): ?Cart
    {
        $query = Cart::where('status', 'active');

        if ($userId) {
            return $query->where('user_id', $userId)->first();
        }

        if ($sessionToken) {
            return $query->where('session_token', $sessionToken)->first();
        }

        return null;
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    public function convertToOrder(Cart $cart, array $orderData)
    {
        return DB::transaction(function () use ($cart, $orderData) {
            $order = Order::create([
                'user_id' => $cart->user_id,
                'session_token' => $cart->session_token,
                'total_amount' => $cart->total,
                'discount_amount' => $orderData['discount_amount'] ?? 0,
                'final_amount' => $orderData['final_amount'] ?? $cart->total,
                'paid_from_wallet' => $orderData['paid_from_wallet'] ?? 0,
                'remaining_amount' => $orderData['remaining_amount'] ?? 0,
                'payment_method' => $orderData['payment_method'] ?? null,
                'status' => $orderData['status'] ?? 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            if (isset($orderData['paid_from_wallet']) && $orderData['paid_from_wallet'] > 0) {
                $this->deductFromWallet($cart->user_id, $orderData['paid_from_wallet'], $order->id);
            }

            $cart->items()->delete();
            $cart->delete();

            Log::info('Order created in CartService', [
                'order_id' => $order->id,
                'total_amount' => $order->total_amount,
                'final_amount' => $order->final_amount,
                'paid_from_wallet' => $order->paid_from_wallet,
                'remaining_amount' => $order->remaining_amount
            ]);

            return $order;
        });
    }

    public function findOrCreateCart(?int $userId, ?string $sessionToken): Cart
    {
        $query = Cart::where('status', 'active');

        if ($userId) {
            $cart = $query->where('user_id', $userId)->first();
            if ($cart) {
                return $cart;
            }
        }

        if ($sessionToken) {
            $cart = $query->where('session_token', $sessionToken)->first();
            if ($cart) {
                return $cart;
            }
        }

        return Cart::create([
            'user_id' => $userId,
            'session_token' => $sessionToken,
            'status' => 'active'
        ]);
    }

    public function applyCoupon(Cart $cart, string $couponCode): array
    {
        if ($couponCode === 'DISCOUNT10') {
            $discount = (int)($cart->total * 0.1);
            return [
                'success' => true,
                'discount' => $discount,
                'coupon_id' => null,
                'message' => 'کد تخفیف 10% اعمال شد'
            ];
        }

        return [
            'success' => false,
            'discount' => 0,
            'coupon_id' => null,
            'message' => 'کد تخفیف نامعتبر است'
        ];
    }

    public function deductFromWallet(int $userId, int $amount, int $orderId): void
    {
        DB::transaction(function () use ($userId, $amount, $orderId) {
            $wallet = Wallet::where('user_id', $userId)->firstOrFail();

            if ($wallet->balance < $amount) {
                throw new \Exception('موجودی کیف پول کافی نیست');
            }

            $wallet->update([
                'balance' => $wallet->balance - $amount,
            ]);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'purchase',
                'amount' => -$amount,
                'description' => 'کسر برای سفارش شماره ' . $orderId,
                'status' => 'completed',
                'meta' => json_encode(['order_id' => $orderId]),
            ]);
        });
    }

    public function refundToWallet(int $userId, int $amount, int $orderId): void
    {
        DB::transaction(function () use ($userId, $amount, $orderId) {
            $wallet = Wallet::where('user_id', $userId)->firstOrFail();

            $wallet->update([
                'balance' => $wallet->balance + $amount,
            ]);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'refund',
                'amount' => $amount,
                'description' => 'بازگشت وجه برای سفارش ناموفق شماره ' . $orderId,
                'status' => 'completed',
                'meta' => json_encode(['order_id' => $orderId]),
            ]);
        });
    }
}
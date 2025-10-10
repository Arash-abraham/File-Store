<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
        return $cartItem->delete();
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
        $order = Order::create([
            'user_id' => $cart->user_id,
            'session_token' => $cart->session_token,
            'total_amount' => $cart->total,
            'discount_amount' => $orderData['discount_amount'],
            'coupon_id' => null,
            'payment_gateway' => $orderData['payment_gateway'],
            'payment_authority' => null,
            'status' => 'pending',
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
    
        $cart->clear(); 
        return $order;
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
}
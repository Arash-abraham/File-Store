<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart(array $data): CartItem
    {
        return DB::transaction(function () use ($data) {
            $cart = Cart::findOrCreateCart($data['user_id'], $data['session_token']);

            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'] ?? 1;

            if ($quantity > $product->stock) {
                throw new \Exception('موجودی کافی نیست');
            }

            $existingItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingItem) {
                $existingItem->incrementQuantity($quantity);
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

        if ($quantity < 1) {
            throw new \InvalidArgumentException('تعداد باید حداقل ۱ باشد');
        }

        $cartItem->quantity = $quantity;
        $cartItem->updateSubtotal();
        $cartItem->save();

        return $cartItem;
    }

    public function removeFromCart(int $cartItemId): bool
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        return $cartItem->delete();
    }

    public function getCart(?int $userId = null, ?string $sessionToken = null): ?Cart
    {
        if ($userId) {
            return Cart::findByUser($userId);
        }

        if ($sessionToken) {
            return Cart::findBySession($sessionToken);
        }

        return null;
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    public function convertToOrder(Cart $cart, array $orderData = []): Order
    {
        return DB::transaction(function () use ($cart, $orderData) {
            $order = Order::create(array_merge([
                'user_id' => $cart->user_id,
                'total_amount' => $cart->total,
                'status' => 'pending',
            ], $orderData));

            foreach ($cart->items as $cartItem) {
                $order->items()->create([
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
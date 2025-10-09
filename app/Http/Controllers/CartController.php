<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $sessionToken = session()->getId();
            $cart = $this->findOrCreateCart($sessionToken);

            DB::transaction(function () use ($cart, $product) {
                $existingItem = $cart->items()->where('product_id', $product->id)->first();

                if ($existingItem) {
                    $existingItem->increment('quantity');
                    $existingItem->update(['subtotal' => $existingItem->unit_price * $existingItem->quantity]);
                } else {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'unit_price' => $product->original_price,
                        'subtotal' => $product->original_price
                    ]);
                }
            });

            return back()->with('success', 'محصول به سبد خرید اضافه شد');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در افزودن به سبد خرید');
        }
    }

    public function updateCart(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cartItem = CartItem::findOrFail($cartItemId);

            $cartItem->update([
                'quantity' => $request->quantity,
                'subtotal' => $cartItem->unit_price * $request->quantity
            ]);

            return back()->with('success', 'تعداد محصول بروزرسانی شد');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در بروزرسانی سبد خرید');
        }
    }

    public function removeFromCart($cartItemId)
    {
        try {
            CartItem::where('id', $cartItemId)->delete();
            return back()->with('success', 'محصول از سبد خرید حذف شد');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در حذف محصول');
        }
    }

    public function showCart()
    {
        $sessionToken = session()->getId();
        $cart = Cart::where('session_token', $sessionToken)
            ->where('status', 'active')
            ->first();

        $cartItems = collect();
        $total = 0;

        if ($cart) {
            $cartItems = $cart->items()->with('product')->get();
            $total = $cartItems->sum('subtotal');
        }

        return view('cart.show', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function clearCart()
    {
        try {
            $sessionToken = session()->getId();
            $cart = Cart::where('session_token', $sessionToken)->first();
            
            if ($cart) {
                $cart->items()->delete();
            }
            
            return back()->with('success', 'سبد خرید خالی شد');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در خالی کردن سبد خرید');
        }
    }

    private function findOrCreateCart($sessionToken): Cart
    {
        $cart = Cart::where('session_token', $sessionToken)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_token' => $sessionToken,
                'status' => 'active'
            ]);
        }

        return $cart;
    }
}
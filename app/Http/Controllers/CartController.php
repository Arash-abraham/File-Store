<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);
    
        try {
            $data = [
                'user_id' => auth()->id(),
                'session_token' => session()->getId(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ];
    
            $this->cartService->addToCart($data);
    
            if ($request->ajax()) {
                $cart = $this->cartService->getCart(auth()->id(), session()->getId());
                $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
                $total = $cart ? $cart->total : 0;
                $discount = session('discount', 0);
    
                return response()->json([
                    'success' => true,
                    'message' => 'محصول به سبد خرید اضافه شد',
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'discount' => $discount,
                    'html' => view('app.partials.cart-content', [
                        'cartItems' => $cartItems,
                        'total' => $total,
                        'discount' => $discount
                    ])->render()
                ]);
            }
    
            return redirect()->back()->with('success', 'محصول به سبد خرید اضافه شد');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'خطا در افزودن به سبد خرید'
                ], 400);
            }
            return back()->with('error', $e->getMessage() ?: 'خطا در افزودن به سبد خرید');
        }
    }

    

    public function removeFromCart(Request $request, $cartItemId)
    {
        try {
            $this->cartService->removeFromCart($cartItemId);
    
            if ($request->ajax()) {
                $cart = $this->cartService->getCart(auth()->id(), session()->getId());
                $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
                $total = $cart ? $cart->total : 0;
                $discount = session('discount', 0);
    
                return response()->json([
                    'success' => true,
                    'message' => 'محصول از سبد خرید حذف شد',
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'discount' => $discount,
                    'html' => view('app.partials.cart-content', [
                        'cartItems' => $cartItems,
                        'total' => $total,
                        'discount' => $discount
                    ])->render()
                ]);
            }
    
            return redirect()->back()->with('success', 'محصول از سبد خرید حذف شد');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'خطا در حذف محصول'
                ], 400);
            }
            return back()->with('error', $e->getMessage() ?: 'خطا در حذف محصول');
        }
    }

    public function showCart()
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = collect();
        $total = 0;
        $discount = 0;
    
        if ($cart) {
            $cartItems = $cart->items()->with('product')->get();
            $total = $cart->total;
            $discount = session('discount', 0);
        }
    
        return view('app.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'discount' => $discount
        ]);
    }
    public function clearCart(Request $request)
    {
        try {
            $cart = $this->cartService->getCart(auth()->id(), session()->getId());
            if ($cart) {
                $this->cartService->clearCart($cart);
            }
            session()->forget('discount');

            if ($request->ajax()) {
                $cart = $this->cartService->getCart(auth()->id(), session()->getId());
                $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
                $total = $cart ? $cart->total : 0;
                $discount = session('discount', 0);

                return response()->json([
                    'success' => true,
                    'message' => 'سبد خرید خالی شد',
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'discount' => $discount,
                    'html' => view('app.partials.cart-content', [
                        'cartItems' => $cartItems,
                        'total' => $total,
                        'discount' => $discount
                    ])->render()
                ]);
            }

            return redirect()->route('cart.show')->with('success', 'سبد خرید خالی شد');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'خطا در خالی کردن سبد خرید'
                ], 400);
            }
            return back()->with('error', $e->getMessage() ?: 'خطا در خالی کردن سبد خرید');
        }
    }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        try {
            $cart = $this->cartService->getCart(auth()->id(), session()->getId());
            if (!$cart) {
                throw new \Exception('سبد خرید یافت نشد');
            }

            $couponResult = $this->cartService->applyCoupon($cart, $request->coupon_code);
            if ($couponResult['success']) {
                session(['discount' => $couponResult['discount']]);
                if ($request->ajax()) {
                    $cart = $this->cartService->getCart(auth()->id(), session()->getId());
                    $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
                    $total = $cart ? $cart->total : 0;
                    $discount = session('discount', 0);

                    return response()->json([
                        'success' => true,
                        'message' => $couponResult['message'],
                        'cartItems' => $cartItems,
                        'total' => $total,
                        'discount' => $discount,
                        'html' => view('app.partials.cart-content', [
                            'cartItems' => $cartItems,
                            'total' => $total,
                            'discount' => $discount
                        ])->render()
                    ]);
                }
                return redirect()->route('cart.show')->with('success', $couponResult['message']);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $couponResult['message']
                ], 400);
            }
            return back()->with('error', $couponResult['message']);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'خطا در اعمال کد تخفیف'
                ], 400);
            }
            return back()->with('error', $e->getMessage() ?: 'خطا در اعمال کد تخفیف');
        }
    }
}
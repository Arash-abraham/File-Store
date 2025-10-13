<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Payment;
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
        if (!auth()->check()) {
            $message = 'برای افزودن محصول به سبد خرید، وارد حساب کاربری خود شوید';
            
            
            return redirect()->back()->withErrors(['error' => $message])->withInput();

        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);
    
        try {
            $userId = auth()->id();
            $productId = $request->product_id;

            $hasPurchased = Payment::where('user_id', $userId)
                ->whereHas('order.items', function($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->where('status', 'completed') 
                ->exists();

            if ($hasPurchased) {
                $message = 'شما قبلاً این محصول را خریداری کرده‌اید';
                return redirect()->back()->withErrors(['error' => $message])->withInput();

            }
    
            $cart = $this->cartService->getCart($userId, session()->getId());
            if ($cart) {
                $existingCartItem = $cart->items()
                    ->where('product_id', $productId)
                    ->first();
    
                if ($existingCartItem) {
                    $message = 'این محصول در حال حاضر در سبد خرید شما موجود است';
                    return redirect()->back()->withErrors(['error' => $message])->withInput();

                }
            }
    
            $data = [
                'user_id' => $userId,
                'session_token' => session()->getId(),
                'product_id' => $productId,
                'quantity' => $request->quantity ?? 1,
            ];
    
            $this->cartService->addToCart($data);
    
            return redirect()->back()->with('success', 'محصول به سبد خرید اضافه شد');
        } catch (\Exception $e) {
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
            return back()->with('error', $e->getMessage() ?: 'خطا در حذف محصول');
        }
    }

    public function showCart()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

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
        if (!auth()->check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'برای مدیریت سبد وارد حساب کاربری خود شوید'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'برای مدیریت سبد وارد حساب کاربری خود شوید');
        }

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
            return back()->with('error', $e->getMessage() ?: 'خطا در خالی کردن سبد خرید');
        }
    }

    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'برای اعمال کد وارد حساب کاربری خود شوید'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'برای اعمال کد وارد حساب کاربری خود شوید');
        }

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

            return back()->with('error', $couponResult['message']);
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage() ?: 'خطا در اعمال کد تخفیف');
        }
    }
}
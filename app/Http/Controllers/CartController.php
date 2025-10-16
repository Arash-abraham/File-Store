<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
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
                ->whereHas('order.items', function ($query) use ($productId) {
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

            return redirect()->back()->with('add_to_cart', 'محصول به سبد خرید اضافه شد');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'خطا در افزودن به سبد خرید');
        }
    }

    public function removeFromCart(Request $request, $cartItemId)
    {
        try {
            $this->cartService->removeFromCart($cartItemId);

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
        $appliedCoupon = '';
    
        if ($cart) {
            $cartItems = $cart->items()->with('product')->get();
            $total = $cart->total ?? 0;
            $discount = $cart->discount ?? 0;
            $appliedCoupon = $cart->coupon_code ?? '';
            
            // DEBUG
            // dd('Cart Data', [
            //     'cart_id' => $cart->id,
            //     'total' => $total,
            //     'discount' => $discount,
            //     'coupon_code' => $appliedCoupon
            // ]);
        }
    
        return view('app.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'discount' => $discount,
            'appliedCoupon' => $appliedCoupon
        ]);
    }

    public function clearCart(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'برای مدیریت سبد وارد حساب کاربری خود شوید');
        }

        try {
            $cart = $this->cartService->getCart(auth()->id(), session()->getId());
            if ($cart) {
                $this->cartService->clearCart($cart);
                $cart->update(['discount' => 0, 'coupon_code' => null]);
            }

            return redirect()->route('cart.show')->with('success', 'سبد خرید خالی شد');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'خطا در خالی کردن سبد خرید');
        }
    }

    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'برای اعمال کد وارد حساب کاربری خود شوید');
        }

        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        try {
            $cart = $this->cartService->getCart(auth()->id(), session()->getId());
            if (!$cart) {
                return back()->with('error', 'سبد خرید یافت نشد');
            }

            $coupon = Coupon::where('code', $request->coupon_code)
                ->active()
                ->first();

            if (!$coupon || !$coupon->isValid()) {
                return back()->with('error', 'کد تخفیف نامعتبر یا منقضی شده است');
            }

            $discountAmount = $coupon->calculateDiscount($cart->total);
            // dd($discountAmount);
            // dd($request);
            // dd($coupon->max_discount < $discountAmount);
            if($coupon->type == 'percentage') {
                if($coupon->max_discount < $request->price) {
                    // dd($coupon->max_discount < $request->price);
                    $max = $coupon->max_discount;
                    return redirect()->back()->withErrors(['coupon_code' => 'حداکثر مبلغ خرید برای استفاده از کد تخفیف '.number_format($max).' تومان است'])->withInput();
                }
    
                if($coupon->min_order > $request->price) {
                    $order = $coupon->min_order;
                    return redirect()->back()->withErrors(['coupon_code' => 'حداقل مبلغ خرید برای استفاده از کد تخفیف '.number_format($order).' تومان است'])->withInput();
                }    
            }
            else {                
                if($coupon->min_order > $request->price) {
                    $order = $coupon->min_order;
                    return redirect()->back()->withErrors(['coupon_code' => 'حداقل مبلغ خرید برای استفاده از کد تخفیف '.number_format($order).' تومان است'])->withInput();
            }    }
            $cart->update([
                'discount' => $discountAmount,
                'coupon_code' => $request->coupon_code
            ]);
            return redirect()->route('cart.show')->with('applied', 'کد تخفیف با موفقیت اعمال شد');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'خطا در اعمال کد تخفیف');
        }
    }
}
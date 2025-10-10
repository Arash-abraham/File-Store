<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $paymentService;

    public function __construct(CartService $cartService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->paymentService = $paymentService;
    }

    public function showCheckout()
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
    public function processCheckout(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string',
            'payment_gateway' => 'sometimes|string|in:zarinpal',
        ]);

        $cart = $this->cartService->getCart(auth()->id(), $request->session_token);

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'سبد خرید خالی است'
            ], 400);
        }

        try {
            $orderData = [
                'payment_gateway' => $request->payment_gateway ?? 'zarinpal',
                'discount_amount' => session('discount', 0),
            ];

            $order = $this->cartService->convertToOrder($cart, $orderData);
            $paymentResponse = $this->paymentService->createPaymentRequest($order);

            if (!$paymentResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $paymentResponse['message']
                ], 400);
            }

            // ذخیره payment_authority
            $order->update(['payment_authority' => $paymentResponse['authority']]);

            return response()->json([
                'success' => true,
                'message' => 'سفارش با موفقیت ایجاد شد',
                'data' => [
                    'order_id' => $order->id,
                    'payment_url' => $paymentResponse['payment_url'],
                    'authority' => $paymentResponse['authority']
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد سفارش: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verify(Request $request): Response|RedirectResponse
    {
        try {
            if ($request->method() === 'GET') {
                $authority = $request->query('Authority') ?? $request->query('authority');
                $status = $request->query('Status') ?? $request->query('status');
            } elseif ($request->method() === 'POST') {
                $authority = $request->input('Authority') ?? $request->input('authority');
                $status = $request->input('Status') ?? $request->input('status');
            } else {
                return redirect()->route('payment.failed')
                    ->with('error', 'متد درخواست پشتیبانی نمی‌شود');
            }
    
            if (!$authority || !$status) {
                return redirect()->route('payment.failed')
                    ->with('error', 'پارامترهای ضروری پرداخت یافت نشد');
            }
    
            $order = Order::where('payment_authority', $authority)->first();
    
            if (!$order) {
                return redirect()->route('payment.failed')
                    ->with('error', 'سفارش مرتبط با کد پرداخت یافت نشد');
            }
    
            if ($order->status === 'paid') {
                return redirect()->route('payment.success', ['order_id' => $order->id])
                    ->with('info', 'این سفارش قبلاً پرداخت شده است');
            }
    
            if ($status !== 'OK') {
                $order->update(['status' => 'failed']);
                return redirect()->route('payment.failed')
                    ->with('error', 'پرداخت توسط کاربر لغو شد');
            }
    
            $paymentResponse = $this->paymentService->verifyPayment($order, $authority, $status);
    
            if (!$paymentResponse['success']) {
                $order->update(['status' => 'failed']);
                return redirect()->route('payment.failed')
                    ->with('error', $paymentResponse['message'] ?? 'خطا در تأیید پرداخت');
            }
    
            $order->markAsPaid('zarinpal', $paymentResponse['ref_id']);
    
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'gateway' => 'zarinpal',
                'gateway_name' => 'زرین‌پال',
                'amount' => $order->final_amount, 
                'status' => 'completed',
                'transaction_id' => $paymentResponse['ref_id'],
                'external_ref' => $authority,
                'verified_at' => now(),
            ]);
    
            session()->forget('discount');
    
    
            return redirect()->route('payment.success', ['order_id' => $order->id])
                ->with('success', 'پرداخت با موفقیت انجام شد! شماره پیگیری: ' . $paymentResponse['ref_id']);
    
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage(), [
                'authority' => $authority ?? 'unknown',
                'status' => $status ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
    
            return redirect()->route('payment.failed')
                ->with('error', 'خطای سیستمی در تأیید پرداخت. لطفاً با پشتیبانی تماس بگیرید');
        }
    }
}
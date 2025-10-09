<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {
    }   
    /**
    * نمایش سبد خرید کاربر
    */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user(); // از auth:sanctum برای گرفتن کاربر
        $cart = $this->cartService->getCart($user?->id, $request->header('session-token')); // session token از header  
        if (!$cart) {
            return response()->json([
            'success' => true,
            'data' => ['items' => [], 'total' => 0, 'items_count' => 0]
            ]);
        }   
        $cart->load('items.product');   
        return response()->json([
            'success' => true,
            'data' => [
            'id' => $cart->id,
            'items' => $cart->items,
            'total' => $cart->total,
            'items_count' => $cart->items_count
            ]
        ]);
    }   
    /**
    * اضافه کردن محصول به سبد خرید
    */
    public function store(Request $request): JsonResponse {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]); 
        try {
            $cartItem = $this->cartService->addToCart([
                'user_id' => $request->user()?->id,
                'session_token' => $request->header('session-token'),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1
            ]); 
            return response()->json([
                'success' => true,
                'message' => 'محصول به سبد خرید اضافه شد',
                'data' => $cartItem->load('product')
            ], 201);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در افزودن به سبد خرید: ' . $e->getMessage()
            ], 500);
        }
    }   
    /**
    * به‌روزرسانی تعداد محصول
    */
    public function update(Request $request, int $cartItemId): JsonResponse {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]); 
        try {
            $cartItem = $this->cartService->updateQuantity($cartItemId, $request->quantity);    
            return response()->json([
                'success' => true,
                'message' => 'تعداد محصول به‌روزرسانی شد',
                'data' => $cartItem
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
            'success' => false,
            'message' => $e->getMessage()
            ], 400);
        }
    }   
    /**
    * حذف محصول از سبد خرید
    */
    public function destroy(int $cartItemId): JsonResponse {
    try {
        $deleted = $this->cartService->removeFromCart($cartItemId); 
        return response()->json([
            'success' => true,
            'message' => 'محصول از سبد خرید حذف شد',
            'data' => ['deleted' => $deleted]
        ]); 
    }
    catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در حذف محصول: ' . $e->getMessage()
        ], 500);
    }
    }   
    /**
    * خالی کردن سبد خرید
    */
    public function clear(Request $request): JsonResponse {
        $user = $request->user();
        $cart = $this->cartService->getCart($user?->id, $request->header('session-token')); 
        if ($cart) {
            $this->cartService->clearCart($cart);
        }   
        return response()->json([
            'success' => true,
            'message' => 'سبد خرید خالی شد'
        ]);
    }
}
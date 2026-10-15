<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(['data' => $this->cartPayload($this->activeCart($request->user()->id))]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()->active()->findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $cart = $this->activeCart($request->user()->id, true);
        $unitPrice = (int) $product->final_price;

        DB::transaction(function () use ($cart, $product, $quantity, $unitPrice) {
            $item = $cart->items()->where('product_id', $product->id)->lockForUpdate()->first();
            $newQuantity = min(20, ($item?->quantity ?? 0) + $quantity);

            if ($item) {
                $item->update([
                    'quantity' => $newQuantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $newQuantity,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $newQuantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $newQuantity,
                ]);
            }
        });

        return response()->json(['data' => $this->cartPayload($cart->fresh())], 201);
    }

    public function update(Request $request, int $itemId)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:20']]);
        $cart = $this->activeCart($request->user()->id);
        $item = $cart->items()->with('product')->findOrFail($itemId);
        $unitPrice = (int) $item->product->final_price;

        $item->update([
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $data['quantity'],
        ]);

        return response()->json(['data' => $this->cartPayload($cart->fresh())]);
    }

    public function remove(Request $request, int $itemId)
    {
        $cart = $this->activeCart($request->user()->id);
        $cart->items()->whereKey($itemId)->firstOrFail()->delete();

        if ($cart->items()->count() === 0) {
            $cart->update(['discount' => 0, 'coupon_code' => null]);
        }

        return response()->json(['data' => $this->cartPayload($cart->fresh())]);
    }

    public function clear(Request $request)
    {
        $cart = $this->activeCart($request->user()->id);
        $cart->items()->delete();
        $cart->update(['discount' => 0, 'coupon_code' => null]);

        return response()->json(['data' => $this->cartPayload($cart->fresh())]);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:100']]);
        $cart = $this->activeCart($request->user()->id);
        $subtotal = $this->subtotal($cart);

        if ($subtotal < 1) {
            return response()->json(['message' => 'سبد خرید شما خالی است.'], 422);
        }

        $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($data['code']))])->first();
        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'کد تخفیف معتبر نیست یا تاریخ آن گذشته است.'], 422);
        }

        $discount = (int) $coupon->calculateDiscount($subtotal);
        if ($discount < 1) {
            return response()->json(['message' => 'شرایط استفاده از این کد تخفیف فراهم نیست.'], 422);
        }

        $cart->update(['coupon_code' => $coupon->code, 'discount' => $discount]);

        return response()->json([
            'message' => 'کد تخفیف اعمال شد.',
            'data' => $this->cartPayload($cart->fresh()),
        ]);
    }

    public function removeCoupon(Request $request)
    {
        $cart = $this->activeCart($request->user()->id);
        $cart->update(['coupon_code' => null, 'discount' => 0]);

        return response()->json(['data' => $this->cartPayload($cart->fresh())]);
    }

    private function activeCart(int $userId, bool $create = false): Cart
    {
        $cart = Cart::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if ($cart || !$create) {
            return $cart ?? new Cart(['user_id' => $userId, 'status' => 'active']);
        }

        return Cart::query()->create(['user_id' => $userId, 'status' => 'active']);
    }

    private function cartPayload(Cart $cart): array
    {
        $items = $cart->exists
            ? $cart->items()->with(['product.category'])->get()
            : collect();

        $lines = $items->map(function (CartItem $item) {
            $price = (int) ($item->product?->final_price ?? $item->unit_price);
            $cover = $item->product?->image_urls[0] ?? null;
            $coverUrl = $cover && !Str::startsWith((string) $cover, ['http://', 'https://', '//'])
                ? asset(ltrim((string) $cover, '/'))
                : $cover;

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'title' => $item->product?->title ?? 'محصول حذف‌شده',
                'slug' => $item->product?->slug,
                'cover_url' => $coverUrl,
                'quantity' => (int) $item->quantity,
                'unit_price' => $price,
                'subtotal' => $price * (int) $item->quantity,
            ];
        })->values();

        $subtotal = (int) $lines->sum('subtotal');
        $discount = min($subtotal, (int) ($cart->discount ?? 0));

        return [
            'id' => $cart->id,
            'items' => $lines,
            'items_count' => (int) $lines->sum('quantity'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_code' => $cart->coupon_code,
            'total' => max(0, $subtotal - $discount),
        ];
    }

    private function subtotal(Cart $cart): int
    {
        return (int) $cart->items()->with('product')->get()->sum(function (CartItem $item) {
            return (int) ($item->product?->final_price ?? $item->unit_price) * (int) $item->quantity;
        });
    }
}

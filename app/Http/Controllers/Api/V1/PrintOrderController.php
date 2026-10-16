<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PrintHouse;
use App\Models\PrintOrder;
use App\Models\Product;
use App\Services\PrintPricingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrintOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = PrintOrder::query()
            ->where('user_id', $request->user()->id)
            ->with(['printHouse:id,name,slug,city,phone', 'product:id,title,slug,image_urls'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $orders->getCollection()->map(fn (PrintOrder $order) => $this->orderPayload($order)),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Price preview for the order form. Uses the same service as order creation,
     * so what the reader sees is exactly what gets charged.
     *
     * The endpoint is public on purpose: a guest must see the price before the
     * form asks them to sign in. The loyalty discount is only granted when the
     * request carries a signed-in reader who owns the digital edition.
     */
    public function quote(Request $request, PrintPricingService $pricing)
    {
        $validated = $request->validate([
            'print_house_id' => ['required', 'integer', 'exists:print_houses,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'paper_type' => ['nullable', Rule::in(array_keys(PrintPricingService::PAPER_SURCHARGES))],
            'binding' => ['nullable', Rule::in(array_keys(PrintPricingService::BINDING_LABELS))],
            'color_print' => ['nullable', 'boolean'],
        ]);

        $house = PrintHouse::query()->active()->findOrFail($validated['print_house_id']);
        $reader = $request->user('sanctum');
        $ownsDigital = !empty($validated['product_id'])
            && $reader !== null
            && $reader->ownsProduct((int) $validated['product_id']);

        $quote = $pricing->quote($house, $validated, $ownsDigital);

        return response()->json([
            'data' => array_merge($quote, [
                'print_house' => ['id' => $house->id, 'name' => $house->name, 'city' => $house->city],
                'digital_owner' => $ownsDigital,
            ]),
        ]);
    }

    public function store(Request $request, PrintPricingService $pricing)
    {
        $validated = $request->validate([
            'print_house_id' => ['required', 'integer', 'exists:print_houses,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'paper_type' => ['required', Rule::in(array_keys(PrintPricingService::PAPER_SURCHARGES))],
            'binding' => ['required', Rule::in(array_keys(PrintPricingService::BINDING_LABELS))],
            'color_print' => ['sometimes', 'boolean'],
            'shipping_name' => ['required', 'string', 'max:150'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'shipping_city' => ['required', 'string', 'max:80'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $house = PrintHouse::query()->active()->findOrFail($validated['print_house_id']);
        $product = Product::query()->active()->findOrFail($validated['product_id']);

        $ownsDigital = $user->ownsProduct($product->id);
        $quote = $pricing->quote($house, $validated, $ownsDigital);

        $order = PrintOrder::query()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'print_house_id' => $house->id,
            'product_title' => $product->title,
            'print_house_name' => $house->name,
            'quantity' => $pricing->clampQuantity($house, (int) $validated['quantity']),
            'paper_type' => $validated['paper_type'],
            'binding' => $validated['binding'],
            'color_print' => (bool) ($validated['color_print'] ?? false),
            'shipping_name' => $validated['shipping_name'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_address' => $validated['shipping_address'],
            'postal_code' => $validated['postal_code'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'unit_price' => $quote['unit_price'],
            'delivery_fee' => $quote['delivery_fee'],
            'discount_amount' => $quote['discount_amount'],
            'total_price' => $quote['total_price'],
            'digital_owner' => $ownsDigital,
            'delivery_days' => $quote['delivery_days'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'سفارش نسخه‌ی چاپی ثبت شد؛ پس از تأیید چاپخانه، وضعیت را در حساب کاربری می‌بینی.',
            'data' => $this->orderPayload($order->load(['printHouse:id,name,slug,city,phone', 'product:id,title,slug,image_urls'])),
        ], 201);
    }

    /**
     * A reader can call off an order while the printing house has not started
     * to work on it yet.
     */
    public function cancel(Request $request, int $id)
    {
        $order = PrintOrder::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'این سفارش پردازش شده است و دیگر قابل لغو نیست؛ با پشتیبانی تماس بگیرید.',
            ], 422);
        }

        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return response()->json([
            'message' => 'سفارش چاپی لغو شد.',
            'data' => $this->orderPayload($order->fresh(['printHouse:id,name,slug,city,phone', 'product:id,title,slug,image_urls'])),
        ]);
    }

    private function orderPayload(PrintOrder $order): array
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'product' => [
                'id' => $order->product_id,
                'title' => $order->product_title,
                'slug' => $order->product?->slug,
                'cover_url' => $this->publicImageUrl($order->product?->image_urls[0] ?? null),
            ],
            'print_house' => [
                'id' => $order->print_house_id,
                'name' => $order->print_house_name,
                'city' => $order->printHouse?->city,
                'phone' => $order->printHouse?->phone,
            ],
            'quantity' => (int) $order->quantity,
            'paper_type' => $order->paper_type,
            'paper_label' => PrintPricingService::PAPER_LABELS[$order->paper_type] ?? $order->paper_type,
            'binding' => $order->binding,
            'binding_label' => PrintPricingService::BINDING_LABELS[$order->binding] ?? $order->binding,
            'color_print' => (bool) $order->color_print,
            'unit_price' => (int) $order->unit_price,
            'delivery_fee' => (int) $order->delivery_fee,
            'discount_amount' => (int) $order->discount_amount,
            'total_price' => (int) $order->total_price,
            'digital_owner' => (bool) $order->digital_owner,
            'delivery_days' => (int) $order->delivery_days,
            'tracking_code' => $order->tracking_code,
            'can_cancel' => $order->status === 'pending',
            'shipping' => [
                'name' => $order->shipping_name,
                'phone' => $order->shipping_phone,
                'city' => $order->shipping_city,
                'address' => $order->shipping_address,
                'postal_code' => $order->postal_code,
            ],
            'notes' => $order->notes,
            'created_at' => $order->created_at?->toISOString(),
        ];
    }

    private function publicImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return str_starts_with($path, 'http') || str_starts_with($path, '//')
            ? $path
            : asset(ltrim($path, '/'));
    }
}

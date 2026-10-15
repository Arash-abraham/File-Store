<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FileProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function overview(Request $request)
    {
        $user = $request->user();
        $orders = Order::query()->where('user_id', $user->id);
        $wallet = Wallet::query()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        return response()->json([
            'data' => [
                'user' => $this->userPayload($user),
                'stats' => [
                    'orders_count' => (clone $orders)->count(),
                    'paid_orders_count' => (clone $orders)->where('status', 'paid')->count(),
                    'total_spent' => (int) (clone $orders)->where('status', 'paid')->selectRaw('COALESCE(SUM(total_amount - discount_amount), 0) as total_spent')->value('total_spent'),
                    'downloads_count' => OrderItem::query()
                        ->whereHas('order', fn ($query) => $query->where('user_id', $user->id)->where('status', 'paid'))
                        ->count(),
                ],
                'wallet' => [
                    'balance' => (int) $wallet->balance,
                    'transactions' => $wallet->transactions()->latest()->limit(5)->get()->map(fn ($transaction) => [
                        'id' => $transaction->id,
                        'type' => $transaction->type,
                        'amount' => (int) $transaction->amount,
                        'description' => $transaction->description,
                        'status' => $transaction->status,
                        'created_at' => $transaction->created_at?->toISOString(),
                    ]),
                ],
                'recent_orders' => (clone $orders)->with('items')->latest()->limit(5)->get()->map(fn (Order $order) => $this->orderPayload($order)),
                'open_tickets' => Ticket::query()->where('user_id', $user->id)->whereIn('status', ['open', 'in_progress'])->count(),
            ],
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json(['data' => ['user' => $this->userPayload($request->user())]]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'marketing_opt_in' => ['sometimes', 'boolean'],
        ]);

        $user->update($data);

        return response()->json(['message' => 'اطلاعات حساب ذخیره شد.', 'data' => ['user' => $this->userPayload($user->fresh())]]);
    }

    public function orders(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $orders->getCollection()->map(fn (Order $order) => $this->orderPayload($order)),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function downloads(Request $request)
    {
        $productIds = OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->pluck('product_id')
            ->unique();

        $files = FileProduct::query()
            ->whereIn('product_id', $productIds)
            ->with('product:id,title,slug,image_urls')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (FileProduct $file) => [
                'id' => $file->id,
                'name' => $file->name,
                'type' => $file->type,
                'size' => $file->size_label,
                'product' => [
                    'id' => $file->product?->id,
                    'title' => $file->product?->title,
                    'slug' => $file->product?->slug,
                    'cover_url' => $this->publicImageUrl($file->product?->image_urls[0] ?? null),
                ],
                'download_url' => route('api.v1.account.downloads.download', $file->id),
            ]);

        return response()->json(['data' => $files]);
    }

    public function download(Request $request, int $fileId)
    {
        $file = FileProduct::query()->findOrFail($fileId);
        $allowed = OrderItem::query()
            ->where('product_id', $file->product_id)
            ->whereHas('order', fn ($query) => $query->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->exists();

        abort_unless($allowed, 403, 'این فایل در کتابخانه‌ی شما نیست.');
        abort_unless(Storage::disk('private')->exists($file->path), 404, 'فایل هنوز در فضای دانلود قرار نگرفته است.');

        return Storage::disk('private')->download($file->path, basename($file->path));
    }

    public function tickets(Request $request)
    {
        $tickets = Ticket::query()->where('user_id', $request->user()->id)->latest()->paginate(10);

        return response()->json([
            'data' => $tickets->items(),
            'meta' => ['current_page' => $tickets->currentPage(), 'last_page' => $tickets->lastPage(), 'total' => $tickets->total()],
        ]);
    }

    public function createTicket(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:10000'],
        ]);

        $ticket = Ticket::query()->create([
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return response()->json(['message' => 'درخواست شما ثبت شد.', 'data' => $ticket], 201);
    }

    private function publicImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '//'])
            ? $path
            : asset(ltrim($path, '/'));
    }

    private function userPayload($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'avatar_url' => $user->avatar_url,
            'birthdate' => $user->birthdate?->toDateString(),
            'marketing_opt_in' => (bool) $user->marketing_opt_in,
        ];
    }

    private function orderPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'total_amount' => (int) $order->total_amount,
            'discount_amount' => (int) $order->discount_amount,
            'final_amount' => $order->final_amount,
            'payment_method' => $order->payment_method,
            'paid_at' => $order->paid_at?->toISOString(),
            'created_at' => $order->created_at?->toISOString(),
            'items' => $order->relationLoaded('items') ? $order->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'title' => $item->product_title,
                'unit_price' => (int) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'subtotal' => (int) $item->subtotal,
            ])->values() : [],
        ];
    }
}

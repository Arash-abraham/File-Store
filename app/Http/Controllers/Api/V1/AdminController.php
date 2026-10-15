<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\FileProduct;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Tag;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'data' => [
                'stats' => [
                    'products' => Product::query()->count(),
                    'users' => User::query()->where('role', 'user')->count(),
                    'orders' => Order::query()->count(),
                    'revenue' => (int) Order::query()->where('status', 'paid')->selectRaw('COALESCE(SUM(total_amount - discount_amount), 0) as revenue')->value('revenue'),
                    'pending_orders' => Order::query()->where('status', 'pending')->count(),
                    'open_tickets' => Ticket::query()->whereIn('status', ['open', 'in_progress'])->count(),
                    'pending_reviews' => Review::query()->where('status', 'pending')->count(),
                ],
                'recent_orders' => Order::query()->with('user:id,name,email')->latest()->limit(8)->get()->map(fn (Order $order) => [
                    'id' => $order->id,
                    'customer' => $order->user?->name ?? 'کاربر حذف‌شده',
                    'amount' => $order->final_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at?->toISOString(),
                ]),
                'sales_last_7_days' => Order::query()
                    ->where('status', 'paid')
                    ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
                    ->selectRaw('DATE(paid_at) as day, SUM(total_amount - discount_amount) as amount')
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get(),
            ],
        ]);
    }

    public function products()
    {
        $products = Product::query()
            ->with('category')
            ->withCount('files')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(20);

        return ProductResource::collection($products)->additional([
            'meta' => $this->paginationMeta($products),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', 'alpha_dash', 'unique:products,slug'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'original_price' => ['required', 'integer', 'min:0'],
            'price' => ['nullable', 'integer', 'min:0', 'lte:original_price'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive', 'draft'])],
            'availability' => ['sometimes', 'boolean'],
            'image_urls' => ['nullable', 'array', 'max:8'],
            'image_urls.*' => ['string', 'max:500'],
            'key_features' => ['nullable', 'array', 'max:20'],
            'key_features.*' => ['string', 'max:250'],
        ]);

        $product = Product::query()->create($data);
        return response()->json(['message' => 'محصول ساخته شد.', 'data' => $product->load('category')], 201);
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = Product::query()->findOrFail($id);
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'slug' => ['sometimes', 'required', 'string', 'max:200', 'alpha_dash', Rule::unique('products', 'slug')->ignore($id)],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'original_price' => ['sometimes', 'required', 'integer', 'min:0'],
            'price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'draft'])],
            'availability' => ['sometimes', 'boolean'],
            'image_urls' => ['nullable', 'array', 'max:8'],
            'image_urls.*' => ['string', 'max:500'],
            'key_features' => ['nullable', 'array', 'max:20'],
            'key_features.*' => ['string', 'max:250'],
        ]);
        $product->update($data);
        return response()->json(['message' => 'محصول به‌روزرسانی شد.', 'data' => $product->fresh()->load('category')]);
    }

    public function deleteProduct(int $id)
    {
        Product::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'محصول حذف شد.']);
    }

    public function categories()
    {
        return response()->json(['data' => Category::query()->withCount('products')->orderBy('name')->get()]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'alpha_dash', 'unique:categories,slug'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);
        return response()->json(['data' => Category::query()->create($data)], 201);
    }

    public function updateCategory(Request $request, int $id)
    {
        $category = Category::query()->findOrFail($id);
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'slug' => ['sometimes', 'required', 'string', 'max:150', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($id)],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);
        $category->update($data);
        return response()->json(['data' => $category->fresh()]);
    }

    public function deleteCategory(int $id)
    {
        $category = Category::query()->findOrFail($id);
        if ($category->products()->exists()) {
            return response()->json(['message' => 'ابتدا محصولات این دسته‌بندی را جابه‌جا کنید.'], 422);
        }
        $category->delete();
        return response()->json(['message' => 'دسته‌بندی حذف شد.']);
    }

    public function tags()
    {
        return response()->json(['data' => Tag::query()->withCount('products')->orderBy('name')->get()]);
    }

    public function storeTag(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'alpha_dash', 'unique:tags,slug'],
        ]);
        return response()->json(['data' => Tag::query()->create($data)], 201);
    }

    public function updateTag(Request $request, int $id)
    {
        $tag = Tag::query()->findOrFail($id);
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'slug' => ['sometimes', 'required', 'string', 'max:150', 'alpha_dash', Rule::unique('tags', 'slug')->ignore($id)],
        ]);
        $tag->update($data);
        return response()->json(['data' => $tag->fresh()]);
    }

    public function deleteTag(int $id)
    {
        Tag::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'برچسب حذف شد.']);
    }

    public function coupons()
    {
        return response()->json(['data' => Coupon::query()->latest()->paginate(20)->items()]);
    }

    public function storeCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:coupons,code'],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'min_order' => ['sometimes', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ]);
        $data['code'] = mb_strtoupper($data['code']);
        $coupon = Coupon::query()->create($data);
        return response()->json(['data' => $coupon], 201);
    }

    public function updateCoupon(Request $request, int $id)
    {
        $coupon = Coupon::query()->findOrFail($id);
        $data = $request->validate([
            'code' => ['sometimes', 'required', 'string', 'max:100', 'alpha_dash', Rule::unique('coupons', 'code')->ignore($id)],
            'type' => ['sometimes', Rule::in(['percentage', 'fixed'])],
            'value' => ['sometimes', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'min_order' => ['sometimes', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ]);
        if (isset($data['code'])) {
            $data['code'] = mb_strtoupper($data['code']);
        }
        $coupon->update($data);
        return response()->json(['data' => $coupon->fresh()]);
    }

    public function deleteCoupon(int $id)
    {
        Coupon::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'کد تخفیف حذف شد.']);
    }

    public function faqs()
    {
        return response()->json(['data' => Faq::query()->orderBy('id')->get()]);
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:300'],
            'answer' => ['required', 'string'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
        ]);
        return response()->json(['data' => Faq::query()->create($data)], 201);
    }

    public function updateFaq(Request $request, int $id)
    {
        $faq = Faq::query()->findOrFail($id);
        $data = $request->validate([
            'question' => ['sometimes', 'required', 'string', 'max:300'],
            'answer' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
        ]);
        $faq->update($data);
        return response()->json(['data' => $faq->fresh()]);
    }

    public function deleteFaq(int $id)
    {
        Faq::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'سوال متداول حذف شد.']);
    }

    public function menus()
    {
        return response()->json(['data' => Menu::query()->orderBy('sort_order')->get()]);
    }

    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'target' => ['sometimes', Rule::in(['_self', '_blank'])],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'description' => ['nullable', 'string'],
        ]);
        return response()->json(['data' => Menu::query()->create($data)], 201);
    }

    public function updateMenu(Request $request, int $id)
    {
        $menu = Menu::query()->findOrFail($id);
        $menu->update($request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'url' => ['sometimes', 'required', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'target' => ['sometimes', Rule::in(['_self', '_blank'])],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'description' => ['nullable', 'string'],
        ]));
        return response()->json(['data' => $menu->fresh()]);
    }

    public function deleteMenu(int $id)
    {
        Menu::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'پیوند منو حذف شد.']);
    }

    public function tickets()
    {
        $tickets = Ticket::query()->with('user:id,name,email')->latest()->paginate(20);
        return response()->json(['data' => $tickets->items(), 'meta' => $this->paginationMeta($tickets)]);
    }

    public function updateTicket(Request $request, int $id)
    {
        $ticket = Ticket::query()->findOrFail($id);
        $ticket->update($request->validate([
            'status' => ['required', Rule::in(['open', 'in_progress', 'closed'])],
            'response' => ['nullable', 'string', 'max:10000'],
            'assigned_to' => ['nullable', 'string', 'max:150'],
        ]));
        if ($ticket->response) {
            $ticket->response_time = now();
            $ticket->save();
        }
        return response()->json(['data' => $ticket->fresh()]);
    }

    public function reviews(Request $request)
    {
        $query = Review::query()->with(['user:id,name', 'product:id,title,slug'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $reviews = $query->paginate(20);
        return response()->json(['data' => $reviews->items(), 'meta' => $this->paginationMeta($reviews)]);
    }

    public function updateReview(Request $request, int $id)
    {
        $review = Review::query()->findOrFail($id);
        $review->update($request->validate(['status' => ['required', Rule::in(['approved', 'pending', 'rejected'])]]));
        return response()->json(['data' => $review->fresh()]);
    }

    public function deleteReview(int $id)
    {
        Review::query()->findOrFail($id)->delete();
        return response()->json(['message' => 'دیدگاه حذف شد.']);
    }

    public function payments()
    {
        $payments = Payment::query()->with(['user:id,name,email', 'order:id,status,total_amount,discount_amount'])->latest()->paginate(25);
        return response()->json(['data' => $payments->items(), 'meta' => $this->paginationMeta($payments)]);
    }

    public function settings()
    {
        $settings = WebSetting::query()->first();
        if (!$settings) {
            $settings = WebSetting::query()->create([
                'site_title' => 'فایل‌استور',
                'site_description' => 'فروشگاه کتاب و محصولات دیجیتال',
            ]);
        }
        return response()->json(['data' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'site_title' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'icon_path' => ['nullable', 'string', 'max:255'],
        ]);
        $settings = WebSetting::query()->first();
        $settings ? $settings->update($data) : $settings = WebSetting::query()->create($data);
        return response()->json(['message' => 'تنظیمات ذخیره شد.', 'data' => $settings->fresh()]);
    }

    public function uploadProductImage(Request $request)
    {
        $data = $request->validate(['image' => ['required', 'image', 'max:5120']]);
        $path = $data['image']->store('products', 'public');
        return response()->json(['data' => ['path' => $path, 'url' => Storage::disk('public')->url($path)]], 201);
    }

    public function productFiles(int $productId)
    {
        $files = FileProduct::query()->where('product_id', $productId)->orderBy('sort_order')->get();
        return response()->json(['data' => $files->map(fn (FileProduct $file) => [
            'id' => $file->id,
            'name' => $file->name,
            'type' => $file->type,
            'size_label' => $file->size_label,
            'sort_order' => $file->sort_order,
        ])]);
    }

    public function storeProductFile(Request $request, int $productId)
    {
        Product::query()->findOrFail($productId);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:30720'],
            'type' => ['required', Rule::in(['zip', 'rar', 'pdf', 'mp4', 'txt', 'other'])],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
        ]);

        $path = $data['file']->store('product-files/'.$productId, 'private');
        $file = FileProduct::query()->create([
            'product_id' => $productId,
            'name' => $data['name'],
            'path' => $path,
            'size_label' => $this->formatBytes($data['file']->getSize()),
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? 1,
        ]);

        return response()->json(['message' => 'فایل محصول بارگذاری شد.', 'data' => $file], 201);
    }

    public function deleteProductFile(int $productId, int $fileId)
    {
        $file = FileProduct::query()->where('product_id', $productId)->findOrFail($fileId);
        Storage::disk('private')->delete($file->path);
        $file->delete();
        return response()->json(['message' => 'فایل حذف شد.']);
    }

    private function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $bytes;
        $unit = 0;
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        return round($size, 1).' '.$units[$unit];
    }
}

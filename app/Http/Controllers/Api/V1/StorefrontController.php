<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Product;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    public function home()
    {
        $products = Product::query()
            ->active()
            ->with('category')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->active()])
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => [
                'settings' => $this->settingsPayload(),
                'categories' => CategoryResource::collection($categories),
                'featured_products' => ProductResource::collection($products),
                'faqs' => Faq::query()->where('status', 'published')->orderBy('id')->limit(5)->get(['id', 'question', 'answer']),
                'stats' => [
                    'products' => Product::active()->count(),
                    'categories' => $categories->count(),
                    'customers' => DB::table('users')->where('role', 'user')->count(),
                    'secure_payments' => true,
                ],
            ],
        ]);
    }

    public function products(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:150'],
            'sort' => ['nullable', 'in:popular,newest,price_asc,price_desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:48'],
        ]);

        $query = Product::query()
            ->active()
            ->with('category')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews');

        if (!empty($validated['q'])) {
            $search = trim($validated['q']);
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($validated['category'])) {
            $category = $validated['category'];
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $category));
        }

        match ($validated['sort'] ?? 'popular') {
            'newest' => $query->latest(),
            'price_asc' => $query->orderByRaw('COALESCE(price, original_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(price, original_price) DESC'),
            default => $query->latest(),
        };

        $products = $query->paginate($validated['per_page'] ?? 12)->withQueryString();

        return ProductResource::collection($products)->additional([
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function product(string $slug)
    {
        $product = Product::query()
            ->active()
            ->where('slug', $slug)
            ->with(['category', 'files' => fn ($query) => $query->orderBy('sort_order')])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->firstOrFail();

        $reviews = $product->approvedReviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => [
                'product' => (new ProductResource($product))->resolve(),
                'files' => $product->files->map(fn ($file) => [
                    'id' => $file->id,
                    'name' => $file->name,
                    'size' => $file->size_label,
                    'type' => $file->type,
                ])->values(),
                'reviews' => $reviews->getCollection()->map(fn ($review) => [
                    'id' => $review->id,
                    'rating' => (int) $review->rating,
                    'body' => $review->body,
                    'author' => $review->user?->name ?? 'کاربر فایل‌استور',
                    'created_at' => $review->created_at?->toISOString(),
                ])->values(),
                'reviews_meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'total' => $reviews->total(),
                ],
            ],
        ]);
    }

    public function categories()
    {
        $categories = Category::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->active()])
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function faqs()
    {
        return response()->json([
            'data' => Faq::query()
                ->where('status', 'published')
                ->orderBy('id')
                ->get(['id', 'question', 'answer']),
        ]);
    }

    public function navigation()
    {
        return response()->json([
            'data' => Menu::query()
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get(['id', 'title', 'url', 'icon', 'target']),
        ]);
    }

    public function settings()
    {
        return response()->json(['data' => $this->settingsPayload()]);
    }

    private function settingsPayload(): array
    {
        $settings = WebSetting::query()->first();

        return [
            'site_title' => $settings?->site_title ?? config('app.name', 'فایل‌استور'),
            'site_description' => $settings?->site_description ?? 'کتاب‌ها و محصولات دیجیتال، برای مسیر یادگیری شما.',
            'phone' => $settings?->phone,
            'email' => $settings?->email,
            'address' => $settings?->address,
            'logo_url' => $settings?->logo_path ? asset($settings->logo_path) : null,
        ];
    }
}

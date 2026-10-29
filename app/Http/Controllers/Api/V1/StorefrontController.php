<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\PageResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Product;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    /**
     * Correlated subquery for the average approved rating of a product.
     * Used both for filtering (`min_rating`) and for ordering by rating.
     */
    private const RATING_SUBQUERY = '(SELECT COALESCE(AVG(reviews.rating), 0) FROM reviews WHERE reviews.product_id = products.id AND reviews.status = ? AND reviews.type = \'comment\' AND reviews.parent_id IS NULL AND reviews.rating IS NOT NULL)';

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
            'tag' => ['nullable', 'string', 'max:150'],
            'sort' => ['nullable', 'in:popular,newest,price_asc,price_desc,rating,discount'],
            'price_min' => ['nullable', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'min:0'],
            'min_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'on_sale' => ['nullable', 'boolean'],
            'has_files' => ['nullable', 'boolean'],
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

        if (!empty($validated['tag'])) {
            $tag = $validated['tag'];
            $query->whereHas('tags', fn ($builder) => $builder->where('slug', $tag));
        }

        // Prices are compared on the effective price the customer actually pays.
        if (isset($validated['price_min'])) {
            $query->whereRaw('COALESCE(price, original_price) >= ?', [(int) $validated['price_min']]);
        }

        if (isset($validated['price_max'])) {
            $query->whereRaw('COALESCE(price, original_price) <= ?', [(int) $validated['price_max']]);
        }

        if (!empty($validated['on_sale'])) {
            $query->whereNotNull('price')->whereColumn('price', '<', 'original_price');
        }

        if (!empty($validated['has_files'])) {
            $query->whereHas('files');
        }

        $minimumRating = (float) ($validated['min_rating'] ?? 0);

        if ($minimumRating > 0) {
            $query->whereRaw(self::RATING_SUBQUERY . ' >= ?', ['approved', $minimumRating]);
        }

        match ($validated['sort'] ?? 'popular') {
            'newest' => $query->latest(),
            'price_asc' => $query->orderByRaw('COALESCE(price, original_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(price, original_price) DESC'),
            'rating' => $query->orderByRaw(self::RATING_SUBQUERY . ' DESC', ['approved']),
            'discount' => $query->orderByRaw(
                'CASE WHEN original_price > 0 AND price IS NOT NULL THEN (original_price - price) / original_price ELSE 0 END DESC',
            ),
            'popular' => $query->orderByDesc('approved_reviews_count'),
            default => $query->latest(),
        };

        $query->orderByDesc('id');

        $products = $query->paginate($validated['per_page'] ?? 12)->withQueryString();

        return ProductResource::collection($products)->additional([
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'filters' => $this->activeFilters($validated),
            ],
        ]);
    }

    /**
     * Instant-search payload for the storefront search box.
     */
    public function searchSuggest(Request $request)
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:1', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $term = trim($validated['q']);
        $limit = (int) ($validated['limit'] ?? 5);

        $products = Product::query()
            ->active()
            ->with('category')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->where(function ($builder) use ($term) {
                $builder->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->orderByDesc('approved_reviews_count')
            ->limit($limit)
            ->get();

        $categories = Category::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->active()])
            ->where('name', 'like', "%{$term}%")
            ->orderBy('name')
            ->limit(4)
            ->get();

        return response()->json([
            'data' => [
                'query' => $term,
                'products' => ProductResource::collection($products),
                'categories' => CategoryResource::collection($categories),
            ],
        ]);
    }

    /**
     * Echo back only the filters the caller actually applied.
     */
    private function activeFilters(array $validated): array
    {
        $keys = ['q', 'category', 'tag', 'price_min', 'price_max', 'min_rating', 'on_sale', 'has_files'];

        return collect($validated)
            ->only($keys)
            ->reject(fn ($value) => $value === null || $value === '' || $value === false)
            ->all();
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
        $related = Product::query()
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->orderByDesc('approved_reviews_count')
            ->latest()
            ->limit(3)
            ->get();
        if ($related->count() < 3) {
            $excludedIds = $related->pluck('id')->push($product->id)->all();
            $related = $related->concat(Product::query()
                ->active()
                ->whereNotIn('id', $excludedIds)
                ->with('category')
                ->withAvg('approvedReviews', 'rating')
                ->withCount('approvedReviews')
                ->orderByDesc('approved_reviews_count')
                ->latest()
                ->limit(3 - $related->count())
                ->get());
        }

        return response()->json([
            'data' => [
                'product' => (new ProductResource($product))->resolve(),
                'related_products' => ProductResource::collection($related)->resolve(),
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

    public function page(string $slug)
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => (new PageResource($page))->resolve()]);
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

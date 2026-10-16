<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Http\Resources\Api\V1\PrintHouseResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\FileProduct;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Page;
use App\Models\Payment;
use App\Models\PrintContractRequest;
use App\Models\PrintHouse;
use App\Models\PrintOrder;
use App\Models\Product;
use App\Models\Review;
use App\Models\Tag;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function pages()
    {        return response()->json([
            'data' => Page::query()->orderBy('slug')->get()->map(fn (Page $page) => (new PageResource($page))->resolve()),
        ]);
    }

    public function updatePage(Request $request, int $id)
    {
        $page = Page::query()->findOrFail($id);

        // Drop rows an admin left completely blank before validating, so a freshly
        // added (but unused) editor row never fails the request.
        if (is_array($request->input('content'))) {
            $request->merge(['content' => $this->normalizePageContent($request->input('content'))]);
        }

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'subtitle' => ['sometimes', 'nullable', 'string', 'max:300'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'content' => ['sometimes', 'nullable', 'array'],
            'content.story' => ['sometimes', 'nullable', 'array'],
            'content.story.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.story.paragraphs' => ['sometimes', 'nullable', 'array', 'max:12'],
            'content.story.paragraphs.*' => ['nullable', 'string', 'max:4000'],
            'content.founders' => ['sometimes', 'nullable', 'array'],
            'content.founders.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.founders.items' => ['sometimes', 'nullable', 'array', 'max:8'],
            'content.founders.items.*.name' => ['required_with:content.founders.items', 'string', 'max:120'],
            'content.founders.items.*.role' => ['nullable', 'string', 'max:150'],
            'content.founders.items.*.bio' => ['nullable', 'string', 'max:2000'],
            'content.responsibilities' => ['sometimes', 'nullable', 'array'],
            'content.responsibilities.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.responsibilities.items' => ['sometimes', 'nullable', 'array', 'max:12'],
            'content.responsibilities.items.*.icon' => ['nullable', 'string', 'max:40'],
            'content.responsibilities.items.*.title' => ['required_with:content.responsibilities.items', 'string', 'max:200'],
            'content.responsibilities.items.*.description' => ['nullable', 'string', 'max:1000'],
            'content.timeline' => ['sometimes', 'nullable', 'array'],
            'content.timeline.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.timeline.items' => ['sometimes', 'nullable', 'array', 'max:12'],
            'content.timeline.items.*.year' => ['nullable', 'string', 'max:20'],
            'content.timeline.items.*.title' => ['required_with:content.timeline.items', 'string', 'max:200'],
            'content.timeline.items.*.description' => ['nullable', 'string', 'max:1000'],
            'content.mission_vision' => ['sometimes', 'nullable', 'array'],
            'content.mission_vision.mission' => ['sometimes', 'nullable', 'array'],
            'content.mission_vision.mission.title' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.mission_vision.mission.icon' => ['sometimes', 'nullable', 'string', 'max:40'],
            'content.mission_vision.mission.body' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'content.mission_vision.vision' => ['sometimes', 'nullable', 'array'],
            'content.mission_vision.vision.title' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.mission_vision.vision.icon' => ['sometimes', 'nullable', 'string', 'max:40'],
            'content.mission_vision.vision.body' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'content.stats' => ['sometimes', 'nullable', 'array'],
            'content.stats.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.stats.items' => ['sometimes', 'nullable', 'array', 'max:8'],
            'content.stats.items.*.value' => ['required_with:content.stats.items', 'string', 'max:40'],
            'content.stats.items.*.label' => ['required_with:content.stats.items', 'string', 'max:120'],
            'content.contact' => ['sometimes', 'nullable', 'array'],
            'content.contact.heading' => ['sometimes', 'nullable', 'string', 'max:200'],
            'content.contact.body' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'content.contact.links' => ['sometimes', 'nullable', 'array', 'max:8'],
            'content.contact.links.*.icon' => ['nullable', 'string', 'max:40'],
            'content.contact.links.*.label' => ['required_with:content.contact.links', 'string', 'max:150'],
            'content.contact.links.*.url' => ['required_with:content.contact.links', 'string', 'max:500'],
        ]);

        $page->update($data);

        return response()->json([
            'message' => 'محتوای صفحه ذخیره شد.',
            'data' => (new PageResource($page->fresh()))->resolve(),
        ]);
    }

    /**
     * Drop empty list rows so a partially filled admin form never stores blanks.
     */
    private function normalizePageContent(array $content): array
    {
        $listSections = [
            'story' => ['paragraphs'],
            'founders' => ['items'],
            'responsibilities' => ['items'],
            'timeline' => ['items'],
            'stats' => ['items'],
            'contact' => ['links'],
        ];

        foreach ($listSections as $section => $keys) {
            foreach ($keys as $key) {
                if (!isset($content[$section][$key]) || !is_array($content[$section][$key])) {
                    continue;
                }

                $content[$section][$key] = array_values(array_filter(
                    $content[$section][$key],
                    static function ($row) use ($section, $key): bool {
                        if ($section === 'story' && $key === 'paragraphs') {
                            return is_string($row) && trim($row) !== '';
                        }

                        return is_array($row) && collect($row)->contains(fn ($value) => is_string($value) && trim($value) !== '');
                    },
                ));
            }
        }

        return $content;
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
        $query = Review::query()
            ->with(['user:id,name', 'product:id,title,slug', 'parent:id,body,type,user_id', 'parent.user:id,name'])
            ->withCount('replies')
            ->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
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
        $review = Review::query()->findOrFail($id);
        $paths = Review::query()->where('id', $review->id)->orWhere('parent_id', $review->id)->pluck('image_path')->filter();
        $review->delete();
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
        return response()->json(['message' => 'دیدگاه حذف شد.']);
    }

    public function printHouses()
    {
        return response()->json([
            'data' => PrintHouse::query()
                ->withCount('printOrders')
                ->orderBy('name')
                ->get()
                ->map(fn (PrintHouse $house) => (new PrintHouseResource($house))->resolve()),
        ]);
    }

    public function storePrintHouse(Request $request)
    {
        $data = $this->validatePrintHouse($request);

        return response()->json([
            'message' => 'چاپخانه ثبت شد.',
            'data' => (new PrintHouseResource(PrintHouse::query()->create($data)))->resolve(),
        ], 201);
    }

    public function updatePrintHouse(Request $request, int $id)
    {
        $house = PrintHouse::query()->findOrFail($id);
        $data = $this->validatePrintHouse($request, $house->id);
        $house->update($data);

        return response()->json([
            'message' => 'اطلاعات چاپخانه به‌روز شد.',
            'data' => (new PrintHouseResource($house->fresh()))->resolve(),
        ]);
    }

    public function deletePrintHouse(int $id)
    {
        $house = PrintHouse::query()->findOrFail($id);

        if ($house->printOrders()->whereIn('status', ['pending', 'confirmed', 'printing'])->exists()) {
            return response()->json(['message' => 'این چاپخانه سفارش در جریان دارد؛ ابتدا آن‌ها را تعیین وضعیت کن.'], 422);
        }

        $house->delete();

        return response()->json(['message' => 'چاپخانه حذف شد.']);
    }

    public function printContracts()
    {
        return response()->json([
            'data' => PrintContractRequest::query()->latest()->get(),
        ]);
    }

    public function updatePrintContract(Request $request, int $id)
    {
        $contract = PrintContractRequest::query()->findOrFail($id);
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'reviewing', 'approved', 'rejected'])],
        ]);
        $contract->update($data);

        return response()->json(['message' => 'وضعیت درخواست قرارداد به‌روز شد.', 'data' => $contract->fresh()]);
    }

    public function printOrders(Request $request)
    {
        $orders = PrintOrder::query()
            ->with(['user:id,name,email', 'printHouse:id,name,city'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(25);

        return response()->json([
            'data' => $orders->getCollection()->map(fn (PrintOrder $order) => [
                'id' => $order->id,
                'status' => $order->status,
                'product_title' => $order->product_title,
                'print_house' => $order->printHouse?->name ?? $order->print_house_name,
                'customer' => $order->user?->name ?? 'کاربر حذف‌شده',
                'customer_email' => $order->user?->email,
                'quantity' => (int) $order->quantity,
                'total_price' => (int) $order->total_price,
                'delivery_days' => (int) $order->delivery_days,
                'shipping_city' => $order->shipping_city,
                'created_at' => $order->created_at?->toISOString(),
            ]),
            'meta' => $this->paginationMeta($orders),
        ]);
    }

    public function updatePrintOrder(Request $request, int $id)
    {
        $order = PrintOrder::query()->findOrFail($id);
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'printing', 'shipped', 'delivered', 'cancelled'])],
        ]);
        $order->update($data);

        return response()->json(['message' => 'وضعیت سفارش چاپی به‌روز شد.', 'data' => ['id' => $order->id, 'status' => $order->status]]);
    }

    private function validatePrintHouse(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'alpha_dash', Rule::unique('print_houses', 'slug')->ignore($ignoreId)],
            'city' => ['required', 'string', 'max:80'],
            'province' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:2000'],
            'capabilities' => ['nullable', 'array', 'max:10'],
            'capabilities.*' => ['string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'unit_price' => ['required', 'integer', 'min:0'],
            'hardcover_surcharge' => ['nullable', 'integer', 'min:0'],
            'delivery_fee' => ['nullable', 'integer', 'min:0'],
            'delivery_days' => ['nullable', 'integer', 'min:1', 'max:90'],
            'min_quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'max_quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));
        }

        return $data;
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

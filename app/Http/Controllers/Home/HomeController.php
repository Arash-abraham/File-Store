<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Tag;
use App\Models\WebSetting;
use App\Services\CartService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();

        $products = Product::where('status', 'active')->take(8)->get();
        $categories = Category::withCount('products')->get();
        $menus = Menu::all();
        $setting = WebSetting::all();
        return view('app.index', compact('setting','menus','cartItems', 'total', 'discount', 'products', 'categories', 'count'));
    }

    public function about() {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();
        $products = Product::where('status', 'active')->take(8)->get();
        $categories = Category::withCount('products')->get();
        $menus = Menu::all();
        $setting = WebSetting::all();
        return view('app.about', compact('setting','menus','cartItems', 'total', 'discount', 'products', 'categories', 'count'));

    }

    public function products(Request $request)
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();

        $query = Product::with('category');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('availability') && $request->availability !== 'all') {
            $query->where('availability', $request->availability === 'available');
        }

        if ($request->filled('price_min')) {
            $query->where('original_price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('original_price', '<=', $request->price_max);
        }

        if ($request->filled('categories')) {
            $categoryIds = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereIn('category_id', $categoryIds);
        }

        $products = $query->paginate();
        $categories = Category::all();
        $selectedCategory = $request->get('category');
        $menus = Menu::all();
        $setting = WebSetting::all();

        return view('app.products', compact('setting','menus','products', 'categories', 'cartItems', 'total', 'discount', 'count', 'selectedCategory'));
    }

    public function productsWithCategory(Request $request)
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();

        $categories = Category::all();
        $selectedCategory = $request->get('category');

        $products = Product::where('status', 'active')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->with('category')
            ->latest()
            ->get();
        
        $menus = Menu::all();
        $setting = WebSetting::all();

        return view('app.products', compact('setting','menus','products', 'categories', 'cartItems', 'total', 'discount', 'count', 'selectedCategory'));
    }

    public function faq()
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();

        $categories = Category::all();
        $faqs = Faq::all();
        $menus = Menu::all();
        $setting = WebSetting::all();

        return view('app.faq', compact('setting','menus','faqs', 'categories', 'cartItems', 'total', 'discount', 'count'));
    }
    public function showProduct(string $id)
    {
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();
    
        $product = Product::with(['category', 'approvedReviews.user', 'files'])->findOrFail($id);
    
        $hasPurchased = false;
        $isAdmin = false;
        
        if (auth()->check()) {
            $user = auth()->user();
            $isAdmin = $user->role === 'admin';
            
            $hasPurchased = Payment::where('user_id', $user->id)
                ->whereHas('order', function($query) use ($product) {
                    $query->whereHas('items', function($q) use ($product) {
                        $q->where('product_id', $product->id);
                    });
                })
                ->where('status', 'completed')
                ->exists();
    
            if ($isAdmin) {
                $hasPurchased = true;
            }
        }
    
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) 
            ->where('availability', true) 
            ->inRandomOrder() 
            ->limit(4)
            ->get();
    
        $tag = Tag::findOrFail($product->tag_id);
        $categories = Category::all();
        $menus = Menu::all();
        $setting = WebSetting::all();
    
        return view('app.show-product', compact(
            'relatedProducts',
            'setting',
            'menus',
            'product', 
            'categories', 
            'tag', 
            'cartItems', 
            'total', 
            'discount', 
            'count',
            'hasPurchased',
            'isAdmin'
        ));
    }

    public function search(Request $request) {
        $query = Product::query()->with('category');
        

        // dd('Search request:', $request->all());
        
        if ($request->filled('q')) {
            $searchTerm = trim($request->q);
            // dd('Search term:', ['term' => $searchTerm]);

            
            if ($request->filled('q')) {
                $searchTerm = trim($request->q);
                if (!empty($searchTerm)) {
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('title', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                    });
                }
            }
        }
        
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('price') && $request->price) {
            $priceRange = $request->price;
            if (str_contains($priceRange, '-')) {
                [$min, $max] = explode('-', $priceRange);
                $query->whereBetween('original_price', [(int)$min, (int)$max]);
            } else {
                $query->where('original_price', '>=', (int)$priceRange);
            }
        }
        
        if ($request->has('availability') && !is_null($request->availability)) {
            $availability = (int)$request->availability;
            $query->where('availability', $availability);
        }
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $query->orderBy('original_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('original_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        $cart = $this->cartService->getCart(auth()->id(), session()->getId());
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $total = $cart ? $cart->total : 0;
        $discount = session('discount', 0);
        $count = $cartItems->count();
        $setting = WebSetting::all();
        $menus = Menu::all();

        return view('app.search', compact('menus','setting','cartItems','total','discount','count','products', 'categories'));
    }
}
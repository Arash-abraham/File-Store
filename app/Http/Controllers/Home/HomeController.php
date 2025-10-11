<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Tag;
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
        $cartItems = collect();
        $total = 0;
        $discount = 0;

        if ($cart) {
            $cartItems = $cart->items()->with('product')->get();
            $total = $cart->total;
            $discount = session('discount', 0);
        }

        $products = Product::where('status', 'active')->take(8)->get(); 
        $categories = Category::withCount('products')->get();

        return view('app.index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'discount' => $discount,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    public function category() {
        return view('app.category');
    }
    public function products(Request $request){
        $query = Product::with('category');
    
        if ($request->has('availability') && $request->availability !== 'all') {
            $query->where('availability', $request->availability === 'available');
        }
        
        if ($request->filled('price_min')) {
            $minPrice = $request->price_min; 
            $query->where('original_price', '>=', $minPrice);
        }
        
        if ($request->filled('price_max')) {
            $maxPrice = $request->price_max; 
            $query->where('original_price', '<=', $maxPrice);
        }
        
        if ($request->filled('categories')) {
            $categoryIds = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereIn('category_id', $categoryIds);
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('app.products', compact('products', 'categories'));
    }
    public function productsWithCategory(Request $request) {
        $categories = Category::all();
        $selectedCategory = $request->get('category');
        
        $products = Product::where('status', 'active')
            ->when($selectedCategory, function($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->with('category')
            ->latest()
            ->get();
        return view('app.products', compact('categories', 'products', 'selectedCategory'));
    }
    public function faq() {
        $categories = Category::all();
        $faqs = Faq::all();
        return view('app.faq',compact('faqs','categories'));
    }
    public function showProduct(string $id) {
        $product = Product::findOrFail($id);
        $tag = Tag::findOrFail($product['tag_id']);
        $categories = Category::all();
        return view('app.show-product',compact('product','categories','tag'));
    }
}

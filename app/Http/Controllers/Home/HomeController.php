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
        $categories = Category::all(); 

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
    public function products() {
        $categories = Category::all();
        return view('app.products',compact('categories'));
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

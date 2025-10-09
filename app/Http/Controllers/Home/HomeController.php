<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $categories = Category::all();
        $products = Product::all();
        return view('app.index',compact('categories','products'));
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

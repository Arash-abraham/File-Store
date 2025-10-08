<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
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
        return view('app.products');
    }
    public function faq() {
        $faqs = Faq::all();
        return view('app.faq',compact('faqs'));
    }
}

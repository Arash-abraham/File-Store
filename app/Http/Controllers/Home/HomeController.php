<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('app.index');
    }
    public function category() {
        return view('app.category');
    }
    public function products() {
        return view('app.products');
    }
    public function faq() {
        return view('app.faq');
    }
}

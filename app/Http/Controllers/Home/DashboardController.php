<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
    
        $orders = Order::where('user_id', $user->id)
            ->with(['product' => function($query) {
                $query->select('id', 'title', 'original_price');
            }])
            ->orderBy('created_at', 'desc');
        return view('dashboard',compact('orders'));
    }

}

<?php
// new update -> Arash-abraham

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\SmsLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use Application\Controllers\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category', [HomeController::class, 'category'])->name('category');
Route::get('/product', [HomeController::class, 'products'])->name('products');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/show-product/{id}/product', [HomeController::class, 'showProduct'])->name('show-product');
Route::get('/product/category', [HomeController::class, 'productsWithCategory'])->name('productsWithCategory');
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update/{cartItemId}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
});

Route::get('about',[HomeController::class,'about'])->name('about');
Route::get('search',[HomeController::class,'search'])->name('search');

Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::match(['get', 'post'], '/verify', [CheckoutController::class, 'verify'])->name('payment.verify');
});
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

Route::get('/payment/success/{order_id}', function ($order_id) {
    $order = Order::findOrFail($order_id);
    return view('app.payment-success', ['order' => $order]);
})->name('payment.success');

Route::get('/dashboard', function () {
    $user = auth()->user();

    $purchases = Payment::where('user_id', $user->id)
        ->where('status', 'completed')
        ->with([
            'order.items.product' => function($query) {
                $query->select('id', 'title', 'original_price', 'image_urls');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->get();

    // dd($purchases->first() ? $purchases->first()->order->items : 'No purchases');
        
    $tickets = Ticket::where('user_id', $user->id)->get();

    return view('dashboard', compact('purchases','tickets'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
});


Route::prefix('admin')->middleware([AdminMiddleware::class,'verified'])->name('admin.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::resource('faq',AdminFaqController::class);
    Route::get('faq/{status}/status' , [AdminFaqController::class,'status'])->name('faq.status');
    Route::resource('category',AdminCategoryController::class);
    Route::resource('tag',AdminTagController::class);
    Route::resource('ticket',AdminTicketController::class);
    Route::get('ticket/{process}/process' , [AdminTicketController::class,'process'])->name('ticket.process');
    Route::get('ticket/{closed}/closed' , [AdminTicketController::class,'closed'])->name('ticket.closed');
    Route::resource('product',AdminProductController::class);
    Route::resource('review', AdminCommentController::class)->only(['index', 'show', 'destroy'])->names([
        'index' => 'review.index',
        'show' => 'review.show',
        'destroy' => 'review.destroy',
    ]);
    Route::put('review/{review}/status', [AdminCommentController::class, 'updateStatus'])->name('review.updateStatus');
    Route::get('review/filter/{status}', [AdminCommentController::class, 'filterByStatus'])->name('review.filter');
    
    Route::resource('menu', AdminMenuController::class);
    Route::get('menu/{id}/toggle-status', [AdminMenuController::class, 'toggleStatus'])->name('menu.toggle-status');

    Route::get('web-setting/',[AdminSettingController::class , 'index'])->name('web-setting.index');
    Route::get('web-setting/set',[AdminSettingController::class , 'set'])->name('web-setting.set');
    Route::put('web-setting/update',[AdminSettingController::class , 'update'])->name('web-setting.update');
    
    Route::resource('coupon', AdminCouponController::class);
    Route::get('coupon/{id}/toggle-status', [AdminCouponController::class, 'toggleStatus'])->name('coupon.toggle-status');
    

});

Route::get('/login-email', [OtpLoginController::class, 'showEmailForm'])->name('login.email'); // show view 
Route::post('/send-otp', [OtpLoginController::class, 'sendOtp'])->name('otp.send'); // Send code to user's email
Route::get('/verify-otp', [OtpLoginController::class, 'showOtpForm'])->name('otp.verify'); // show view 
Route::post('/veri fy-otp', [OtpLoginController::class, 'verifyOtp'])->name('otp.verify.submit'); // check code

Route::middleware('web')->group(function () {
    Route::get('/login-SMS', [VerificationController::class, 'showSmsForm'])->name('login.sms');
    Route::get('/verify-SMS', [VerificationController::class, 'showSmsOtpForm'])->name('sms.verify');
    Route::post('/send-code', [VerificationController::class, 'sendCode'])->middleware('throttle:5,1')->name('sms.send');
    Route::post('/verify-code', [VerificationController::class, 'verifyCode'])->name('sms.verify-code');
});

Route::post('/reviews', [ReviewController::class, 'store'])->name('review.store');
Route::post('/reviews/{review}/helpful', [ReviewController::class, 'markHelpful'])->name('review.helpful');
Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->name('review.report');
Route::put('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('review.updateStatus')->middleware('admin');


require __DIR__.'/auth.php';
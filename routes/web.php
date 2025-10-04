<?php
// new update -> Arash-abraham

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\SmsLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category', [HomeController::class, 'category'])->name('category');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware([AdminMiddleware::class,'verified'])->name('admin.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::resource('faq',AdminFaqController::class);
    Route::get('faq/{status}/status' , [AdminFaqController::class,'status'])->name('faq.status');
    Route::resource('category',AdminCategoryController::class);
    Route::resource('tag',AdminTagController::class);
    Route::resource('ticket',AdminTicketController::class);
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

require __DIR__.'/auth.php';    
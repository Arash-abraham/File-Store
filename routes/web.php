<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\OtpLoginController;
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
});

Route::get('/login-email', [OtpLoginController::class, 'showEmailForm'])->name('login.email'); // show view 
Route::post('/send-otp', [OtpLoginController::class, 'sendOtp'])->name('otp.send'); // Send code to user's email
Route::get('/verify-otp', [OtpLoginController::class, 'showOtpForm'])->name('otp.verify'); // show view 
Route::post('/verify-otp', [OtpLoginController::class, 'verifyOtp'])->name('otp.verify.submit'); // check code

require __DIR__.'/auth.php';
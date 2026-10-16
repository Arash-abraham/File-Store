<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\AnalyticsController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AdminStatsController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\PaymentsController;
use App\Http\Controllers\Api\V1\PrintController;
use App\Http\Controllers\Api\V1\PrintOrderController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\StorefrontController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/health', fn () => response()->json(['data' => ['status' => 'ok', 'version' => 'v1']]))->name('health');

    // Public storefront API.
    Route::get('/home', [StorefrontController::class, 'home'])->name('home');
    Route::get('/products', [StorefrontController::class, 'products'])->name('products.index');
    Route::get('/products/{slug}', [StorefrontController::class, 'product'])->name('products.show');
    // Comments and product questions (approved items only, plus the viewer's own pending items).
    Route::get('/products/{slug}/reviews', [ReviewController::class, 'index'])->name('products.reviews.index');
    Route::get('/search/suggest', [StorefrontController::class, 'searchSuggest'])
        ->middleware('throttle:90,1')
        ->name('search.suggest');

    // Physical printing: partner directory and contracting for printing houses.
    Route::get('/print-houses', [PrintController::class, 'index'])->name('print-houses.index');
    Route::post('/print-houses/contract-requests', [PrintController::class, 'contractRequest'])
        ->middleware('throttle:6,1')
        ->name('print-houses.contract');
    Route::get('/categories', [StorefrontController::class, 'categories'])->name('categories.index');
    Route::get('/faqs', [StorefrontController::class, 'faqs'])->name('faqs.index');
    Route::get('/pages/{slug}', [StorefrontController::class, 'page'])->name('pages.show');
    Route::get('/navigation', [StorefrontController::class, 'navigation'])->name('navigation');
    Route::post('/analytics/pageview', [AnalyticsController::class, 'pageView'])
        ->middleware('throttle:120,1')
        ->name('analytics.pageview');
    Route::get('/settings', [StorefrontController::class, 'settings'])->name('settings');

    // Payment gateways return the customer to these public backend callbacks.
    Route::get('/payments/zarinpal/callback', [PaymentsController::class, 'zarinpalCallback'])
        ->name('payments.zarinpal.callback');
    Route::match(['get', 'post'], '/wallet/zarinpal/callback', [WalletController::class, 'zarinpalCallback'])
        ->name('wallet.zarinpal.callback');

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:8,1')->name('login');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('password.forgot');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.reset');
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('email.verify');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
        Route::post('/cart/items', [CartController::class, 'add'])->name('cart.items.store');
        Route::patch('/cart/items/{itemId}', [CartController::class, 'update'])->name('cart.items.update');
        Route::delete('/cart/items/{itemId}', [CartController::class, 'remove'])->name('cart.items.delete');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
        Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
        Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.delete');

        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/account/overview', [AccountController::class, 'overview'])->name('account.overview');
        Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
        Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
        Route::get('/account/library', [AccountController::class, 'library'])->name('account.library');
        Route::get('/account/downloads', [AccountController::class, 'downloads'])->name('account.downloads');
        Route::get('/account/downloads/{fileId}', [AccountController::class, 'download'])->name('account.downloads.download');
        Route::get('/account/tickets', [AccountController::class, 'tickets'])->name('account.tickets');
        Route::post('/account/tickets', [AccountController::class, 'createTicket'])->name('account.tickets.store');
        Route::get('/account/tickets/{ticket}', [AccountController::class, 'showTicket'])->name('account.tickets.show');
        Route::post('/account/tickets/{ticket}/messages', [AccountController::class, 'replyTicket'])
            ->middleware('throttle:20,1')
            ->name('account.tickets.reply');

        Route::get('/print-orders', [PrintOrderController::class, 'index'])->name('print-orders.index');
        Route::post('/print-orders/quote', [PrintOrderController::class, 'quote'])->name('print-orders.quote');
        Route::post('/print-orders', [PrintOrderController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('print-orders.store');

        Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
        Route::post('/wallet/deposits', [WalletController::class, 'deposit'])->middleware('throttle:5,1')->name('wallet.deposits.store');

        Route::post('/reviews', [ReviewController::class, 'store'])
            ->middleware('throttle:20,1')
            ->name('reviews.store');
        Route::post('/reviews/{review}/edit', [ReviewController::class, 'update'])
            ->middleware('throttle:20,1')
            ->name('reviews.update');
        Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful'])->name('reviews.helpful');
        Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');
    });

    Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminStatsController::class, 'dashboard'])->name('dashboard');

        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        Route::post('/uploads/product-image', [AdminController::class, 'uploadProductImage'])->name('uploads.product-image');
        Route::get('/products/{productId}/files', [AdminController::class, 'productFiles'])->name('products.files.index');
        Route::post('/products/{productId}/files', [AdminController::class, 'storeProductFile'])->name('products.files.store');
        Route::delete('/products/{productId}/files/{fileId}', [AdminController::class, 'deleteProductFile'])->name('products.files.delete');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

        Route::get('/tags', [AdminController::class, 'tags'])->name('tags.index');
        Route::post('/tags', [AdminController::class, 'storeTag'])->name('tags.store');
        Route::put('/tags/{id}', [AdminController::class, 'updateTag'])->name('tags.update');
        Route::delete('/tags/{id}', [AdminController::class, 'deleteTag'])->name('tags.delete');

        Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons.index');
        Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
        Route::put('/coupons/{id}', [AdminController::class, 'updateCoupon'])->name('coupons.update');
        Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon'])->name('coupons.delete');

        Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs.index');
        Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faqs.store');
        Route::put('/faqs/{id}', [AdminController::class, 'updateFaq'])->name('faqs.update');
        Route::delete('/faqs/{id}', [AdminController::class, 'deleteFaq'])->name('faqs.delete');

        Route::get('/pages', [AdminController::class, 'pages'])->name('pages.index');
        Route::put('/pages/{id}', [AdminController::class, 'updatePage'])->name('pages.update');

        Route::get('/menus', [AdminController::class, 'menus'])->name('menus.index');
        Route::post('/menus', [AdminController::class, 'storeMenu'])->name('menus.store');
        Route::put('/menus/{id}', [AdminController::class, 'updateMenu'])->name('menus.update');
        Route::delete('/menus/{id}', [AdminController::class, 'deleteMenu'])->name('menus.delete');

        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets.index');
        Route::get('/tickets/{id}', [AdminController::class, 'showTicket'])->name('tickets.show');
        Route::post('/tickets/{id}/messages', [AdminController::class, 'replyTicket'])->name('tickets.reply');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::put('/tickets/{id}', [AdminController::class, 'updateTicket'])->name('tickets.update');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
        Route::put('/reviews/{id}', [AdminController::class, 'updateReview'])->name('reviews.update');
        Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
        Route::get('/print-houses', [AdminController::class, 'printHouses'])->name('print-houses.index');
        Route::post('/print-houses', [AdminController::class, 'storePrintHouse'])->name('print-houses.store');
        Route::put('/print-houses/{id}', [AdminController::class, 'updatePrintHouse'])->name('print-houses.update');
        Route::delete('/print-houses/{id}', [AdminController::class, 'deletePrintHouse'])->name('print-houses.delete');
        Route::get('/print-contracts', [AdminController::class, 'printContracts'])->name('print-contracts.index');
        Route::put('/print-contracts/{id}', [AdminController::class, 'updatePrintContract'])->name('print-contracts.update');
        Route::get('/print-orders', [AdminController::class, 'printOrders'])->name('print-orders.index');
        Route::put('/print-orders/{id}', [AdminController::class, 'updatePrintOrder'])->name('print-orders.update');

        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.show');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});

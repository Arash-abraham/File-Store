<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-code', [VerificationController::class, 'sendCode'])->middleware('throttle:5,1')->name('api.send-code');
Route::post('/verify-code', [VerificationController::class, 'verifyCode'])->name('api.verify-code');


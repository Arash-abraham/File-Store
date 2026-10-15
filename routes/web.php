<?php

use Illuminate\Support\Facades\Route;

// The customer and admin interfaces live in /frontend. Laravel serves only
// JSON APIs; this tiny root response makes the API service self-describing.
Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'service' => 'FileStore API',
        'version' => 'v1',
        'documentation' => '/docs/api',
        'health' => '/up',
    ]);
});

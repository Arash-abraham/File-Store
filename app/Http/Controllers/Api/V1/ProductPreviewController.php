<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductPreviewController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::query()->active()->where('slug', $slug)->firstOrFail();
        abort_unless($product->preview_path && Storage::disk('public')->exists($product->preview_path), 404);

        return response()->file(Storage::disk('public')->path($product->preview_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="book-sample.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}

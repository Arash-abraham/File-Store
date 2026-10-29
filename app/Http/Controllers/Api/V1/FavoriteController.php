<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Models\ProductFavorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = ProductFavorite::query()
            ->where('user_id', $request->user()->id)
            ->whereHas('product', fn ($query) => $query->active())
            ->with(['product.category', 'product' => fn ($query) => $query->withAvg('approvedReviews', 'rating')->withCount('approvedReviews')])
            ->latest('updated_at')
            ->get();

        return response()->json(['data' => $favorites->map(fn (ProductFavorite $favorite) => $this->present($favorite))->values()]);
    }

    public function store(Request $request, int $productId)
    {
        $product = Product::query()->active()->with('category')->findOrFail($productId);
        $favorite = ProductFavorite::query()->updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['saved_price' => $product->final_price],
        );
        $favorite->setRelation('product', $product);

        return response()->json(['message' => 'کتاب به علاقه‌مندی‌ها اضافه شد.', 'data' => $this->present($favorite)], 201);
    }

    public function destroy(Request $request, int $productId)
    {
        ProductFavorite::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'کتاب از علاقه‌مندی‌ها حذف شد.']);
    }

    private function present(ProductFavorite $favorite): array
    {
        $product = $favorite->product;

        return [
            'id' => $favorite->id,
            'product_id' => $product->id,
            'saved_price' => (int) $favorite->saved_price,
            'current_price' => (int) $product->final_price,
            'price_dropped' => (int) $product->final_price < (int) $favorite->saved_price,
            'updated_at' => $favorite->updated_at?->toISOString(),
            'product' => (new ProductResource($product))->resolve(),
        ];
    }
}

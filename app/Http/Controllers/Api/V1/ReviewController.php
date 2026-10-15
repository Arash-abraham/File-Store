<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\ReviewReport;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $hasPurchased = OrderItem::query()
            ->where('product_id', $data['product_id'])
            ->whereHas('order', fn ($query) => $query->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'ثبت دیدگاه برای خریداران این محصول فعال است.'], 403);
        }

        $review = Review::query()->create([
            'product_id' => $data['product_id'],
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'body' => $data['body'],
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'دیدگاه برای بررسی ثبت شد.', 'data' => $review], 201);
    }

    public function helpful(Request $request, Review $review)
    {
        ReviewHelpful::query()->firstOrCreate([
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'بازخورد شما ثبت شد.']);
    }

    public function report(Request $request, Review $review)
    {
        ReviewReport::query()->firstOrCreate([
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'گزارش شما ثبت شد.']);
    }
}

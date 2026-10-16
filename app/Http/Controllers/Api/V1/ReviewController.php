<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\ReviewReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Comments (دیدگاه‌ها) and product questions (پرسش و پاسخ) for buyers.
 *
 * Rules:
 *  - only buyers of a product (paid order) can write comments, questions, replies or edits;
 *  - every written item is `pending` until an admin approves it; edits go back to pending;
 *  - readers (guests and logged-in users) only see approved items, plus their own pending items;
 *  - likes and reports are only possible on approved items.
 */
class ReviewController extends Controller
{
    private const IMAGE_RULE = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

    /** GET /products/{slug}/reviews?type=comment|question&page=1 */
    public function index(Request $request, string $slug): JsonResponse
    {
        $product = Product::query()->active()->where('slug', $slug)->firstOrFail();
        $viewer = $request->user('sanctum');
        $viewerId = $viewer?->id;
        $type = $request->string('type')->toString() === Review::TYPE_QUESTION ? Review::TYPE_QUESTION : Review::TYPE_COMMENT;

        // Readers see approved items. Authors also see their own pending/rejected items (with a status badge).
        $visible = fn ($query) => $query->where('status', 'approved')
            ->when($viewerId, fn ($q) => $q->orWhere(fn ($own) => $own->where('user_id', $viewerId)->whereIn('status', ['pending', 'rejected'])));

        $items = Review::query()
            ->where('product_id', $product->id)
            ->where('type', $type)
            ->whereNull('parent_id')
            ->where($visible)
            ->with([
                'user:id,name',
                'replies' => fn ($query) => $query->where($visible)->with('user:id,name')->withCount('helpfuls')
                    ->when($viewerId, fn ($q) => $q->withExists(['helpfuls as liked_by_me' => fn ($h) => $h->where('user_id', $viewerId)]))
                    ->oldest(),
            ])
            ->withCount('helpfuls')
            ->when($viewerId, fn ($q) => $q->withExists(['helpfuls as liked_by_me' => fn ($h) => $h->where('user_id', $viewerId)]))
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => [
                'items' => $items->getCollection()->map(fn (Review $review) => $this->serialize($review, $viewerId, true))->values(),
                'meta' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'total' => $items->total(),
                ],
                'can_write' => $viewerId ? $this->hasPurchased($viewerId, $product->id) : false,
                'type' => $type,
            ],
        ]);
    }

    /** POST /reviews — create a comment, a question, or a reply (parent_id). */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['nullable', 'in:comment,question'],
            'parent_id' => ['nullable', 'integer', 'exists:reviews,id'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'min:3', 'max:3000'],
            'image' => self::IMAGE_RULE,
        ]);

        $userId = $request->user()->id;
        if (!$this->hasPurchased($userId, (int) $data['product_id'])) {
            return response()->json(['message' => 'ثبت دیدگاه و پرسش برای خریداران این کتاب فعال است.'], 403);
        }

        $type = $data['type'] ?? Review::TYPE_COMMENT;
        $rating = $data['rating'] ?? null;

        if (!empty($data['parent_id'])) {
            $parent = Review::query()->find($data['parent_id']);
            if (!$parent || (int) $parent->product_id !== (int) $data['product_id'] || $parent->parent_id !== null || $parent->status !== 'approved') {
                return response()->json(['message' => 'امکان پاسخ به این مورد وجود ندارد.'], 422);
            }
            // A reply always belongs to the thread type of its parent and carries no rating.
            $type = $parent->type;
            $rating = null;
            $parentId = $parent->id;
        } else {
            $parentId = null;
            if ($type === Review::TYPE_QUESTION) {
                $rating = null;
            }
        }

        $review = Review::query()->create([
            'product_id' => $data['product_id'],
            'user_id' => $userId,
            'type' => $type,
            'parent_id' => $parentId,
            'rating' => $rating,
            'body' => $data['body'],
            'image_path' => $this->storeImage($request),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => $type === Review::TYPE_QUESTION
                ? 'پرسش شما ثبت شد و پس از تأیید مدیر نمایش داده می‌شود.'
                : 'دیدگاه شما ثبت شد و پس از تأیید مدیر نمایش داده می‌شود.',
            'data' => $this->serialize($review->fresh(['user:id,name']), $userId, false),
        ], 201);
    }

    /** POST /reviews/{review}/edit — the author edits body, rating or image. Edited items need approval again. */
    public function update(Request $request, Review $review): JsonResponse
    {
        if ((int) $review->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'فقط نویسنده‌ی این مورد می‌تواند آن را ویرایش کند.'], 403);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:3000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'image' => self::IMAGE_RULE,
            'remove_image' => ['sometimes', 'boolean'],
        ]);

        $isTopLevelComment = $review->parent_id === null && $review->type === Review::TYPE_COMMENT;

        $imagePath = $review->image_path;
        if ($request->hasFile('image') || ($data['remove_image'] ?? false)) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->hasFile('image') ? $this->storeImage($request) : null;
        }

        $review->update([
            'body' => $data['body'],
            'rating' => $isTopLevelComment ? ($data['rating'] ?? null) : null,
            'image_path' => $imagePath,
            'status' => 'pending',
            'edited_at' => now(),
        ]);

        return response()->json([
            'message' => 'ویرایش ثبت شد و پس از تأیید مدیر دوباره نمایش داده می‌شود.',
            'data' => $this->serialize($review->fresh(['user:id,name']), $request->user()->id, false),
        ]);
    }

    /** POST /reviews/{review}/helpful — toggles the current user's like. */
    public function helpful(Request $request, Review $review): JsonResponse
    {
        if ($review->status !== 'approved') {
            return response()->json(['message' => 'این مورد هنوز منتشر نشده است.'], 404);
        }
        if ((int) $review->user_id === (int) $request->user()->id) {
            return response()->json(['message' => 'نمی‌توانید به دیدگاه خودتان رأی دهید.'], 422);
        }

        $existing = ReviewHelpful::query()
            ->where('review_id', $review->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            ReviewHelpful::query()->create(['review_id' => $review->id, 'user_id' => $request->user()->id]);
            $liked = true;
        }

        return response()->json([
            'message' => $liked ? 'پسندیدید.' : 'پسندتان برداشته شد.',
            'data' => ['liked' => $liked, 'likes_count' => $review->helpfuls()->count()],
        ]);
    }

    public function report(Request $request, Review $review): JsonResponse
    {
        if ($review->status !== 'approved') {
            return response()->json(['message' => 'این مورد هنوز منتشر نشده است.'], 404);
        }

        ReviewReport::query()->firstOrCreate([
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'گزارش شما ثبت شد.']);
    }

    private function hasPurchased(int $userId, int $productId): bool
    {
        return OrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('order', fn ($query) => $query->where('user_id', $userId)->where('status', 'paid'))
            ->exists();
    }

    private function storeImage(Request $request): ?string
    {
        return $request->hasFile('image') ? $request->file('image')->store('reviews', 'public') : null;
    }

    private function serialize(Review $review, ?int $viewerId, bool $withReplies): array
    {
        $data = [
            'id' => $review->id,
            'type' => $review->type,
            'parent_id' => $review->parent_id,
            'rating' => $review->rating,
            'body' => $review->body,
            'image_url' => $review->image_url,
            'author' => $review->user?->name ?? 'کاربر فایل‌استور',
            'status' => $review->status,
            'is_mine' => $viewerId !== null && (int) $review->user_id === (int) $viewerId,
            'is_edited' => $review->edited_at !== null,
            'likes_count' => (int) ($review->helpfuls_count ?? $review->helpfuls()->count()),
            'liked_by_me' => (bool) ($review->liked_by_me ?? false),
            'created_at' => $review->created_at?->toISOString(),
            'edited_at' => $review->edited_at?->toISOString(),
        ];

        if ($withReplies) {
            $data['replies'] = $review->replies->map(fn (Review $reply) => $this->serialize($reply, $viewerId, false))->values();
        }

        return $data;
    }
}

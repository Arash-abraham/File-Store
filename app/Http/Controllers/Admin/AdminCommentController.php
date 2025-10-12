<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{

    /**
     * Display a listing of all product reviews.
     */
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->withCount(['helpfuls', 'reports'])
            ->latest()
            ->get();

        $pending_count = Review::pending()->count();
        $approved_count = Review::approved()->count();
        $rejected_count = Review::rejected()->count();

        return view('admin.layouts.sections.reviews.reviews', compact(
            'reviews',
            'pending_count',
            'approved_count',
            'rejected_count'
        ));
    }

    /**
     * Display the specified review.
     */
    public function show(string $id)
    {
        $review = Review::with(['product', 'user'])
            ->withCount(['helpfuls', 'reports'])
            ->findOrFail($id);

        return view('admin.layouts.sections.reviews.show-review', compact('review'));
    }

    /**
     * Update the status of a review (approved, pending, rejected).
     */
    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $review->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'وضعیت نظر با موفقیت تغییر کرد.',
            ]);
        }

        return redirect()->route('admin.review.index')
            ->with('success', 'وضعیت نظر با موفقیت تغییر کرد.');
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review, Request $request)
    {
        $review->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'نظر با موفقیت حذف شد.',
            ]);
        }

        return redirect()->route('admin.review.index')
            ->with('success', 'نظر با موفقیت حذف شد.');
    }

    /**
     * Filter reviews by status.
     */
    public function filterByStatus(Request $request, $status)
    {
        if (!in_array($status, ['approved', 'pending', 'rejected'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'وضعیت نامعتبر است.',
                ], 422);
            }
            return redirect()->route('admin.review.index')
                ->with('error', 'وضعیت نامعتبر است.');
        }

        $reviews = Review::with(['product', 'user'])
            ->withCount(['helpfuls', 'reports'])
            ->where('status', $status)
            ->latest()
            ->get();

        $pending_count = Review::pending()->count();
        $approved_count = Review::approved()->count();
        $rejected_count = Review::rejected()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'pending_count' => $pending_count,
                'approved_count' => $approved_count,
                'rejected_count' => $rejected_count,
            ]);
        }

        return view('admin.layouts.sections.reviews.reviews', compact(
            'reviews',
            'pending_count',
            'approved_count',
            'rejected_count'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\ReviewReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'body' => 'required|string|min:10',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => 5,
            'body' => $request->body,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'نظر شما با موفقیت ثبت شد و در انتظار تأیید است.');
    }

    public function markHelpful(Request $request, Review $review)
    {
        $userId = Auth::id();

        if ($review->hasBeenHelpfulBy($userId)) {
            return redirect()->back()->with('error', 'شما قبلاً این نظر را مفید علامت کرده‌اید.');
        }

        ReviewHelpful::create([
            'review_id' => $review->id,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', 'نظر با موفقیت به‌عنوان مفید علامت شد.');
    }

    public function report(Request $request, Review $review)
    {
        $userId = Auth::id();

        // چک کردن اینکه کاربر قبلاً این نظر رو گزارش نکرده باشه
        if ($review->hasBeenReportedBy($userId)) {
            return redirect()->back()->with('error', 'شما قبلاً این نظر را گزارش کرده‌اید.');
        }

        ReviewReport::create([
            'review_id' => $review->id,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', 'نظر با موفقیت گزارش شد.');
    }

    // // متد برای ادمین برای مدیریت وضعیت نظرات
    // public function updateStatus(Request $request, Review $review)
    // {
    //     $this->middleware('admin'); // فرض بر اینه که میدلویر admin داری

    //     $request->validate([
    //         'status' => 'required|in:approved,pending,rejected',
    //     ]);

    //     $review->update(['status' => $request->status]);

    //     return redirect()->back()->with('success', 'وضعیت نظر با موفقیت تغییر کرد.');
    // }
}
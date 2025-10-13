<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $coupons = Coupon::latest()->get();
        
        $active_count = Coupon::active()->count();
        $inactive_count = Coupon::inactive()->count();
        
        // Count expired coupons
        $expired_count = Coupon::active()
            ->whereNotNull('end_date')
            ->where('end_date', '<', Carbon::now())
            ->count();

        return view('admin.layouts.sections.coupons.coupons', compact(
            'coupons',
            'active_count',
            'inactive_count',
            'expired_count'
        ));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('admin.layouts.sections.coupons.add-coupon');
    }

    /**
     * Store a newly created coupon.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:coupons,code',
                'regex:/^[A-Z0-9]+$/'
            ],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'max_discount' => 'nullable|integer|min:0',
            'min_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ], [
            'code.required' => 'کد تخفیف الزامی است',
            'code.unique' => 'این کد تخفیف قبلاً ثبت شده است',
            'code.regex' => 'کد تخفیف فقط باید شامل حروف بزرگ انگلیسی و اعداد باشد',
            'type.required' => 'نوع تخفیف الزامی است',
            'value.required' => 'مقدار تخفیف الزامی است',
            'value.min' => 'مقدار تخفیف باید حداقل 1 باشد',
            'end_date.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
        ]);

        // Validate percentage value
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'درصد تخفیف نمی‌تواند بیشتر از 100 باشد'])->withInput();
        }

        Coupon::create($validated);

        return redirect()->route('admin.coupon.index')
            ->with('success', 'کد تخفیف با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.layouts.sections.coupons.edit-coupon', compact('coupon'));
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:coupons,code,' . $coupon->id,
                'regex:/^[A-Z0-9]+$/'
            ],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'max_discount' => 'nullable|integer|min:0',
            'min_order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ], [
            'code.required' => 'کد تخفیف الزامی است',
            'code.unique' => 'این کد تخفیف قبلاً ثبت شده است',
            'code.regex' => 'کد تخفیف فقط باید شامل حروف بزرگ انگلیسی و اعداد باشد',
            'type.required' => 'نوع تخفیف الزامی است',
            'value.required' => 'مقدار تخفیف الزامی است',
            'value.min' => 'مقدار تخفیف باید حداقل 1 باشد',
            'end_date.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
        ]);

        // Validate percentage value
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'درصد تخفیف نمی‌تواند بیشتر از 100 باشد'])->withInput();
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupon.index')
            ->with('success', 'کد تخفیف با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return redirect()->route('admin.coupon.index')
            ->with('success', 'کد تخفیف با موفقیت حذف شد');
    }

    /**
     * Toggle coupon status.
     */
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $coupon->status = $coupon->status === 'active' ? 'inactive' : 'active';
        $coupon->save();

        return back()->with('success', 'وضعیت کد تخفیف تغییر یافت');
    }
}


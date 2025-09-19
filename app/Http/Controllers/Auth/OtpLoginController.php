<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpLoginController extends Controller
{
    // نمایش فرم ایمیل
    public function showEmailForm()
    {
        return view('auth.email-login');  // ویو برای وارد کردن ایمیل
    }

    // ارسال OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'ایمیل یافت نشد.']);
        }

        // حذف OTPهای قبلی
        OtpCode::where('email', $request->email)->delete();

        // تولید OTP 6 رقمی
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // ذخیره OTP (با Redis برای expiration بهتر، اما اینجا دیتابیس)
        OtpCode::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // ارسال ایمیل
        $user->notify(new OtpNotification($otp));

        session(['otp_email' => $request->email]);  // ذخیره ایمیل موقت

        return redirect()->route('otp.verify')->with('status', 'کد به ایمیل شما ارسال شد.');
    }

    // نمایش فرم وارد کردن OTP
    public function showOtpForm()
    {
        return view('auth.otp-verify');  // ویو برای وارد کردن OTP
    }

    // تأیید OTP و لاگین
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $email = session('otp_email');

        if (!OtpCode::isValid($email, $request->otp)) {
            return back()->withErrors(['otp' => 'کد نامعتبر یا منقضی شده است.']);
        }

        // استفاده از OTP
        $otpRecord = OtpCode::where('email', $email)->where('otp', $request->otp)->first();
        $otpRecord->use();

        // لاگین کاربر
        $user = User::where('email', $email)->first();
        Auth::login($user);

        // پاک کردن سشن
        session()->forget('otp_email');

        return redirect()->intended(route('dashboard'))->with('status', 'لاگین موفق!');
    }
}
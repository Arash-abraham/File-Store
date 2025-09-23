<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class VerificationController extends Controller
{
    public function sendCode(Request $request, SmsService $smsService)
    {
        // اعتبارسنجی شماره تلفن
        $request->validate([
            'phone_number' => 'required|regex:/^09[0-9]{9}$/', // فرمت شماره ایرانی
        ]);
    
        $phoneNumber = $request->phone_number;
        $code = rand(100000, 999999); // تولید کد 6 رقمی
    
        // حذف کدهای قبلی برای این شماره
        VerificationCode::where('phone_number', $phoneNumber)->delete();
    
        // ذخیره کد در دیتابیس
        VerificationCode::create([
            'phone_number' => $phoneNumber,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);
    
        // ارسال پیامک
        try {
            $smsService->sendSms($phoneNumber, "فایل استور - کد تایید شما: {$code}");
            
            // ذخیره شماره تلفن در session
            session(['phone_number' => $phoneNumber]);
            
            return redirect()->route('sms.verify')->with([
                'success' => 'کد تأیید با موفقیت ارسال شد'
            ]);
        } 
        catch (\Exception $e) {
            return back()->withErrors(['error' => 'خطا در ارسال پیامک: ' . $e->getMessage()]);
        }
    }

    public function verifyCode(Request $request)
    {
        // اعتبارسنجی ورودی
        $request->validate([
            'phone_number' => 'required|regex:/^09[0-9]{9}$/',
            'code' => 'required|digits:6',
        ]);
    
        $phoneNumber = $request->phone_number;
        $code = $request->code;
    
        // بررسی کد
        $verification = VerificationCode::where('phone_number', $phoneNumber)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    
        if (!$verification) {
            // اگر کد نامعتبر باشد، با خطا به صفحه قبل برمی‌گردد
            return back()->withErrors(['code' => 'کد نامعتبر یا منقضی شده است'])->withInput();
        }
    
        // علامت‌گذاری کد به عنوان استفاده‌شده
        $verification->update(['is_used' => true]);
    
        // لاگین یا ثبت‌نام کاربر
        $user = User::firstOrCreate(
            ['phone_number' => $phoneNumber],
            ['name' => 'کاربر', 'password' => bcrypt(Str::random(10))]
        );
    
        // تولید توکن
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // ذخیره توکن در session
        session(['auth_token' => $token]);
        
        // لاگین کردن کاربر
        auth()->login($user);
    
        // ریدایرکت به صفحه dashboard در صورت موفقیت
        return redirect()->route('dashboard')->with([
            'success' => 'ورود با موفقیت انجام شد',
            'user' => $user
        ]);
    }
}
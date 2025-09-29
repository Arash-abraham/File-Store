<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function showSmsForm() {
        return view('auth.sms-login');
    }
    
    public function sendCode(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^09[0-9]{9}$/',
        ]);
    
        $phoneNumber = $request->phone_number;
    
        $userExists = User::where('phone', $phoneNumber)->exists();
        
        if (!$userExists) {
            Log::info('User does not exist', ['phone_number' => $phoneNumber]);
            return redirect()->route('login.sms')
                ->withErrors(['phone_number' => 'شماره تلفن در سیستم ثبت نشده است'])
                ->withInput();
        }
    
        $code = rand(100000, 999999);
        
        VerificationCode::where('phone_number', $phoneNumber)->delete();
        
        VerificationCode::create([
            'phone_number' => $phoneNumber,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);
        
        try {
            // $smsService->sendSms($phoneNumber, "فایل استور - کد تایید شما: {$code}");
            return redirect()
                ->route('sms.verify')
                ->with('success', 'کد تأیید ارسال شد')
                ->with('phone_number', $phoneNumber);
        } catch (\Exception $e) {
            Log::error('SMS Error: ' . $e->getMessage());
            return redirect()->route('login.sms')
                ->withErrors(['phone_number' => 'خطا در ارسال پیامک'])
                ->withInput();
        }
    }
    public function showSmsOtpForm(Request $request) {
        $phone = $request->session()->get('phone_number');
        
        if (!$phone) {
            return redirect()->route('login.sms')->withErrors(['phone_number' => 'شماره تلفن یافت نشد']);
        }
        
        return view('auth.sms-verify', compact('phone'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^09[0-9]{9}$/',
            'code' => 'required|digits:6',
        ]);

        $phoneNumber = $request->phone_number;
        $code = $request->code;
        
        $verification = VerificationCode::where('phone_number', $phoneNumber)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    
        if (!$verification) {
            // dd('test');
            return redirect()->route('sms.verify')->withErrors(['code' => 'کد نامعتبر یا منقضی شده است'])->with('phone_number', $phoneNumber);
        }
    
        $verification->update(['is_used' => true]);
    
        $user = User::where('phone', $phoneNumber)->first();
        Auth::login($user);

        return redirect()->intended(route('dashboard'))->with('status', 'لاگین موفق!');
    }
}
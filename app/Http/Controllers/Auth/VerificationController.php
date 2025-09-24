<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function sendCode(Request $request, SmsService $smsService)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^09[0-9]{9}$/',
        ]);
    
        $phoneNumber = $request->phone_number;
        
        $code = rand(100000, 999999);
    
        VerificationCode::where('phone_number', $phoneNumber)->delete();
    
        VerificationCode::create([
            'phone_number' => $phoneNumber,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);

        return redirect()
        ->route('sms.verify')
        ->with('success', 'کد تأیید ارسال شد')
        ->cookie(
            'verify_phone', 
            $phoneNumber, 
            10,
            '/', // path
            null, // domain 
            false, // secure (localhost)
            false  // httpOnly
        );
        
        try {
            
            $smsService->sendSms($phoneNumber, "فایل استور - کد تایید شما: {$code}");
            
            return redirect()->route('sms.verify', ['phone' => $phoneNumber])->with('success', 'کد تأیید ارسال شد');
            
        } 
        catch (\Exception $e) {
            return back()->withErrors(['phone_number' => 'خطا در ارسال پیامک'])->withInput();
        }
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
            return redirect()->route('sms.verify', ['phone' => $phoneNumber])->withErrors(['code' => 'کد نامعتبر یا منقضی شده است']);
        }
    
        $verification->update(['is_used' => true]);
    
        $user = User::where('phone', $phoneNumber)->first();
        
        if (!$user) {
            return redirect()->route('sms.verify', ['phone' => $phoneNumber])->withErrors(['phone_number' => 'کاربری با این شماره تلفن یافت نشد']);
        }
    
        Auth::login($user);
    
        return redirect()->route('dashboard')->with('success', 'ورود با موفقیت انجام شد');
    }
}
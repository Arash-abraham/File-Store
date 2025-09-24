<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SmsLoginController extends Controller
{
    public function showSmsForm() {
        return view('auth.sms-login');
    }
    
    public function showSmsOtpForm(Request $request) {
        $phone = $_COOKIE['verify_phone'];

        $userExists = User::where('phone', $phone)->exists();
        
        if (!$userExists) {
            return back()->withErrors(['phone_number' => 'شماره تلفن در سیستم ثبت نشده است.']);
        }
        
        return view('auth.sms-verify', compact('phone'));
    }
}
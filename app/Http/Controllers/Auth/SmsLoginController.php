<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsLoginController extends Controller
{
    public function showSmsForm() {
        return view('auth.sms-login');  // show view
    }
    public function showSmsOtpForm() {
        return view('auth.sms-verify');  // show view
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsLoginController extends Controller
{
    public function showSmsForm() {
        return view('auth.sms-login'); 
    }
}

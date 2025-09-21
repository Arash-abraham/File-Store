<?php
// new update -> Arash-abraham

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
    public function showEmailForm() {
        return view('auth.email-login');  // show view
    }

    // send OTP
    public function sendOtp(Request $request) {
        $request->validate(['email' => 'required|email']); // Validation for incoming email

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'ایمیل یافت نشد.']); // If the email does not exist, it will send a "Not Found" error.
        }

        // remove old opt
        OtpCode::where('email', $request->email)->delete();

        // create OTP 6 digit
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // save OTP
        OtpCode::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // send eamil
        $user->notify(new OtpNotification($otp));

        session(['otp_email' => $request->email]);  // save temporary email 

        return redirect()->route('otp.verify')->with('status', 'کد به ایمیل شما ارسال شد.');
    }

    public function showOtpForm() {
        return view('auth.otp-verify');  // show view
    }

    // confirm OTP and login
    public function verifyOtp(Request $request)
    {

        $request->validate(['otp' => 'required|digits:6']);
        // dd($request); // for debug
        $email = session('otp_email');

        if (!OtpCode::isValid($email, $request->otp)) {
            return back()->withErrors(['otp' => 'کد نامعتبر یا منقضی شده است.']);
        }

        // use OTP
        $otpRecord = OtpCode::where('email', $email)->where('otp', $request->otp)->first();
        $otpRecord->use();

        // login
        $user = User::where('email', $email)->first();
        Auth::login($user);

        // remove session
        session()->forget('otp_email');

        return redirect()->intended(route('dashboard'))->with('status', 'لاگین موفق!');
    }
}

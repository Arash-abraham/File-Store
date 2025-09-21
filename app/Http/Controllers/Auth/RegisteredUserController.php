<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required' , 'regex:/^09\d{9}$/' , 'string' , 'unique:users,phone']
        ], //create and check valdation 
        [
            'name.required' => 'لطفاً نام خود را وارد کنید.',
            'name.unique' => 'این نام کاربری قبلاً ثبت شده است. لطفاً نام دیگری انتخاب کنید.',
            'email.required' => 'لطفاً ایمیل خود را وارد کنید.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است. لطفاً ایمیل دیگری وارد کنید.',
            'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
            'password.confirmed' => 'تأیید رمز عبور با رمز عبور وارد شده مطابقت ندارد.',
            'phone.unique' => 'این شماره تماس قبلاً ثبت شده است. لطفاً شماره ای دیگر انتخاب کنید.',
            'email.lowercase' => 'ایمیل خود را با حروف کوچک وارد کنید'
        ]); // error msg
        // dd($request);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone
        ]); // create column
        
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect(route('dashboard', absolute: false));
    }
    
    
}

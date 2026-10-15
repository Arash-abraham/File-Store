<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'lowercase', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => mb_strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
        ]);

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['data' => ['user' => $this->userPayload($user)]], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials['email'] = mb_strtolower($credentials['email']);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'message' => 'ایمیل یا گذرواژه درست نیست.',
                'errors' => ['email' => ['ایمیل یا گذرواژه درست نیست.']],
            ], 422);
        }

        $request->session()->regenerate();
        return response()->json(['data' => ['user' => $this->userPayload($request->user())]]);
    }

    public function me(Request $request)
    {
        return response()->json(['data' => ['user' => $this->userPayload($request->user())]]);
    }

    public function verifyEmail(int $id, string $hash)
    {
        $user = User::query()->findOrFail($id);
        abort_unless(hash_equals(sha1($user->getEmailForVerification()), $hash), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect(config('services.frontend.url').'/account?verified=1');
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        PasswordBroker::sendResetLink(['email' => mb_strtolower($data['email'])]);

        // Do not disclose whether an address exists in the database.
        return response()->json(['message' => 'اگر ایمیل در سامانه ثبت شده باشد، پیوند بازیابی برایتان ارسال می‌شود.']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);
        $data['email'] = mb_strtolower($data['email']);

        $status = PasswordBroker::reset($data, function (User $user, string $password) {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
        });

        if ($status !== PasswordBroker::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json(['message' => 'گذرواژه با موفقیت تغییر کرد.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'با موفقیت خارج شدید.']);
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'avatar_url' => $user->avatar_url,
            'email_verified_at' => $user->email_verified_at?->toISOString(),
        ];
    }
}

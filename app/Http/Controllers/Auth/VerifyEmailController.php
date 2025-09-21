<?php
// new update -> Arash-abraham

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        Log::info('Verification attempt for user: ' . $request->user()->id . ', Email: ' . $request->user()->email);

        if ($request->user()->hasVerifiedEmail()) {
            Log::info('Email already verified for user: ' . $request->user()->id);
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        try {
            $user = $request->user();
            $user->email_verified_at = now();
            if ($user->save()) {
                Log::info('Email verified successfully for user: ' . $user->id);
                event(new Verified($user));
            } else {
                Log::error('Failed to save user model for user: ' . $user->id);
            }
        } catch (\Exception $e) {
            Log::error('Exception during email verification for user: ' . $request->user()->id . ', Error: ' . $e->getMessage());
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
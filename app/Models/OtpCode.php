<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'otp', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // متد برای چک کردن اعتبار OTP
    public static function isValid($email, $otp)
    {
        return self::where('email', $email)
                   ->where('otp', $otp)
                   ->where('used', false)
                   ->where('expires_at', '>', now())
                   ->exists();
    }

    // متد برای استفاده از OTP
    public function use()
    {
        $this->update(['used' => true]);
    }
}
<?php
// new update -> Arash-abraham

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'otp', 'expires_at', 'used'];

    // TIME
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // method for check OPT validation

    public static function isValid($email, $otp) {
        return self::where('email', $email)
            ->where('otp', $otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->exists();
    }

    // method for using OPT
    public function use() {
        $this->update(['used' => true]);
    }
}
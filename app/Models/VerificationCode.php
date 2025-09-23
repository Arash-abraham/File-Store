<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = ['phone_number', 'code', 'expires_at', 'is_used'];
}

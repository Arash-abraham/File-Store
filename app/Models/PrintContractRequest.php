<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintContractRequest extends Model
{
    protected $fillable = [
        'company_name',
        'contact_name',
        'phone',
        'email',
        'city',
        'monthly_capacity',
        'capabilities',
        'message',
        'status',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'monthly_capacity' => 'integer',
    ];
}

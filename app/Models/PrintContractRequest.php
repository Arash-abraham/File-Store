<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'admin_note',
        'print_house_id',
        'reviewed_at',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'monthly_capacity' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /**
     * The printing house that was published for this contract, if the request
     * has already been approved.
     */
    public function printHouse(): BelongsTo
    {
        return $this->belongsTo(PrintHouse::class);
    }
}

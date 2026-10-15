<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'total_amount',
        'discount_amount',
        'coupon_id',
        'payment_gateway',
        'transaction_id',
        'reference',
        'paid_at',
        'paid_from_wallet',
        'remaining_amount',
        'payment_method',
        'status',
        'payment_authority',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'discount_amount' => 'integer',
        'paid_from_wallet' => 'integer',
        'remaining_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getFinalAmountAttribute(): int
    {
        return max(0, (int) $this->total_amount - (int) $this->discount_amount);
    }

    public function markAsPaid(string $paymentGateway, string $transactionId): void
    {
        $this->update([
            'status' => 'paid',
            'payment_gateway' => $paymentGateway,
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }
}

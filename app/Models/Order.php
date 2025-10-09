<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'discount_amount',
        'coupon_id',
        'payment_gateway',
        'transaction_id',
        'reference',
        'paid_at'
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'discount_amount' => 'integer',
        'paid_at' => 'datetime'
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

    public function getFinalAmountAttribute(): int
    {
        return $this->total_amount - $this->discount_amount;
    }

    public function markAsPaid(string $paymentGateway, string $transactionId): void
    {
        $this->update([
            'status' => 'paid',
            'payment_gateway' => $paymentGateway,
            'transaction_id' => $transactionId,
            'paid_at' => now()
        ]);
    }
}
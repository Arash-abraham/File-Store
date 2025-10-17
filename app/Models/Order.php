<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token', 
        'total_amount',
        'discount_amount',
        'final_amount',
        'paid_from_wallet', 
        'remaining_amount', 
        'payment_method',
        'payment_status',
        'status',
        'payment_authority'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'paid_from_wallet' => 'decimal:2', 
        'remaining_amount' => 'decimal:2', 
    ];
    // روابط
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
        $total = (int)$this->total_amount;
        $discount = (int)($this->discount_amount ?? 0);
        return max(0, $total - $discount);
    }

    /*
    public function getTotalAmountAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
    */
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function markAsPaid(string $paymentGateway, string $transactionId): void
    {
        $this->update([
            'status' => 'paid',
            'payment_gateway' => $paymentGateway,
            'payment_authority' => $this->payment_authority ?? null, 
            'transaction_id' => $transactionId,
            'paid_at' => now()
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'description',
        'authority',
        'ref_id',
        'status',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'افزایش موجودی',
            'withdrawal' => 'برداشت',
            'purchase' => 'خرید',
            'refund' => 'عودت',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'در انتظار',
            'completed' => 'تکمیل شده',
            'failed' => 'ناموفق',
            default => $this->status,
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        $sign = in_array($this->type, ['deposit', 'refund']) ? '+' : '-';
        return $sign . number_format($this->amount) . ' تومان';
    }

    public function markAsCompleted(string $refId = null): void
    {
        $this->update([
            'status' => 'completed',
            'ref_id' => $refId,
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
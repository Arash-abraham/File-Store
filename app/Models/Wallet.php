<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function deposit(float $amount, string $description = null, array $meta = []): WalletTransaction
    {
        return $this->createTransaction('deposit', $amount, $description, $meta);
    }

    public function withdraw(float $amount, string $description = null, array $meta = []): WalletTransaction
    {
        if ($this->balance < $amount) {
            throw new \Exception('موجودی کافی نیست');
        }

        return $this->createTransaction('withdrawal', $amount, $description, $meta);
    }

    private function createTransaction(string $type, float $amount, string $description = null, array $meta = []): WalletTransaction
    {
        $transaction = $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'meta' => $meta,
            'status' => 'completed',
        ]);

        $this->updateBalance($type, $amount);

        return $transaction;
    }

    private function updateBalance(string $type, float $amount): void
    {
        if (in_array($type, ['deposit', 'refund'])) {
            $this->increment('balance', $amount);
        } else {
            $this->decrement('balance', $amount);
        }
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance) . ' تومان';
    }

    public function canWithdraw(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
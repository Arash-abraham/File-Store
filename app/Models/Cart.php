<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute(): int
    {
        return $this->items->sum('subtotal');
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public static function findBySession(string $sessionToken): ?self
    {
        return static::where('session_token', $sessionToken)
            ->where('status', 'active')
            ->first();
    }

    public static function findByUser(int $userId): ?self
    {
        return static::where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    public static function findOrCreateCart(?int $userId = null, ?string $sessionToken = null): self
    {
        if ($userId) {
            $cart = static::findByUser($userId);
            if ($cart) return $cart;
        }

        if ($sessionToken) {
            $cart = static::findBySession($sessionToken);
            if ($cart) return $cart;
        }

        return static::create([
            'user_id' => $userId,
            'session_token' => $sessionToken,
            'status' => 'active'
        ]);
    }
}
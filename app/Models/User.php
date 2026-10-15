<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_url',
        'birthdate',
        'marketing_opt_in',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'marketing_opt_in' => 'boolean',
        ];
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /** Compatibility accessor for existing services; create wallets explicitly in API use cases. */
    public function getWalletAttribute(): Wallet
    {
        return $this->wallet()->firstOrCreate([], ['balance' => 0]);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Items from orders that are actually paid for. Ownership of a digital book
     * always resolves through this relation, never through the cart.
     */
    public function paidOrderItems()
    {
        return OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('user_id', $this->id)->where('status', 'paid'));
    }

    /** @return array<int, int> */
    public function ownedProductIds(): array
    {
        return $this->paidOrderItems()->pluck('product_id')->unique()->values()->all();
    }

    public function ownsProduct(int $productId): bool
    {
        return $this->paidOrderItems()->where('product_id', $productId)->exists();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail());
    }
}

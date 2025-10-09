<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'status'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // محاسبه مجموع سبد خرید
    public function getTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }

    // محاسبه تعداد آیتم‌ها
    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }
}
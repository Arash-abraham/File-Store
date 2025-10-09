<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'integer',
        'subtotal' => 'integer'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function updateSubtotal(): void
    {
        $this->subtotal = $this->unit_price * $this->quantity;
    }

    public function incrementQuantity(int $quantity = 1): void
    {
        $this->quantity += $quantity;
        $this->updateSubtotal();
        $this->save();
    }

    public function updateUnitPriceFromProduct(): void
    {
        if ($this->product) {
            $this->unit_price = $this->product->price; 
            $this->updateSubtotal();
        }
    }
}
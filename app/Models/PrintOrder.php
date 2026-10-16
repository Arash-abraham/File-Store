<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintOrder extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'print_house_id',
        'product_title',
        'print_house_name',
        'quantity',
        'paper_type',
        'binding',
        'color_print',
        'shipping_name',
        'shipping_phone',
        'shipping_city',
        'shipping_address',
        'postal_code',
        'notes',
        'unit_price',
        'delivery_fee',
        'discount_amount',
        'total_price',
        'digital_owner',
        'delivery_days',
        'tracking_code',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'color_print' => 'boolean',
        'digital_owner' => 'boolean',
        'unit_price' => 'integer',
        'delivery_fee' => 'integer',
        'discount_amount' => 'integer',
        'total_price' => 'integer',
        'delivery_days' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function printHouse(): BelongsTo
    {
        return $this->belongsTo(PrintHouse::class);
    }
}

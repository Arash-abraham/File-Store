<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrintHouse extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'city',
        'province',
        'description',
        'capabilities',
        'phone',
        'email',
        'address',
        'website',
        'unit_price',
        'hardcover_surcharge',
        'delivery_fee',
        'delivery_days',
        'min_quantity',
        'max_quantity',
        'rating',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'unit_price' => 'integer',
        'hardcover_surcharge' => 'integer',
        'delivery_fee' => 'integer',
        'delivery_days' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'rating' => 'float',
        'is_featured' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function printOrders(): HasMany
    {
        return $this->hasMany(PrintOrder::class);
    }
}

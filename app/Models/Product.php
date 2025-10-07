<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'tag_id',
        'category_id',
        'status',
        'availability',
        'original_price',
        'price',
        'image_urls',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'availability' => 'boolean',
        'original_price' => 'integer',
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'image_urls' => 'array'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include active products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('availability', true);
    }

    /**
     * Get the final price of the product (price or original_price if price is null).
     *
     * @return int
     */
    public function getFinalPriceAttribute(): int
    {
        return $this->price ?? $this->original_price;
    }

    /**
     * Check if the product is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability && $this->status === 'active';
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }


}
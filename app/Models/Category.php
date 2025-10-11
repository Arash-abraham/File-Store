<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','icon','color'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    
    public function getProductsCountAttribute(): int
    {
        return $this->products_count ?? $this->products()->count();
    }
    

    public function scopeWithProductsCount($query)
    {
        return $query->withCount('products');
    }
}

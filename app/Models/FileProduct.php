<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FileProduct extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'path',
        'size_label',
        'type',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the product that owns the file.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the file type with label.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'pdf' => 'PDF',
            'zip' => 'ZIP Archive',
            'rar' => 'RAR Archive',
            default => $this->type,
        };
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Check if file exists in storage.
     *
     * @return bool
     */
    public function getExistsAttribute(): bool
    {
        return Storage::disk('private')->exists($this->path);
    }

    /**
     * Get file size in bytes.
     *
     * @return int|null
     */
    public function getSizeInBytesAttribute(): ?int
    {
        if (!$this->exists) {
            return null;
        }

        return Storage::disk('private')->size($this->path);
    }

    /**
     * Get formatted file size.
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        if ($this->size_label) {
            return $this->size_label;
        }

        $bytes = $this->size_in_bytes;
        
        if ($bytes === null) {
            return 'نامشخص';
        }

        $units = ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope a query to order by sort order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope a query to only include files of a specific product.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope a query to only include files of specific types.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, array $types)
    {
        return $query->whereIn('type', $types);
    }
}
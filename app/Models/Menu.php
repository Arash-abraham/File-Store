<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'sort_order',
        'target',
        'status',
        'description',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive menus
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get status name in Persian
     */
    public function getStatusNameAttribute()
    {
        return $this->status === 'active' ? 'فعال' : 'غیرفعال';
    }

    /**
     * Get target name in Persian
     */
    public function getTargetNameAttribute()
    {
        return $this->target === '_self' ? 'همان صفحه' : 'صفحه جدید';
    }
}


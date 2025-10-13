<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{


    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount',
        'min_order',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'value' => 'integer',
        'max_discount' => 'integer',
        'min_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope for active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive coupons
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if coupon is valid
     */
    public function isValid()
    {
        // Check if active
        if ($this->status !== 'active') {
            return false;
        }

        // Check dates
        $now = Carbon::now();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for given amount
     */
    public function calculateDiscount($orderAmount)
    {
        // Check minimum order
        if ($orderAmount < $this->min_order) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($orderAmount * $this->value) / 100;
            
            // Apply max discount if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        }

        // Fixed discount
        return min($this->value, $orderAmount);
    }

    /**
     * Get formatted discount value
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return number_format($this->value) . ' تومان';
    }

    /**
     * Get type name in Persian
     */
    public function getTypeNameAttribute()
    {
        return $this->type === 'percentage' ? 'درصدی' : 'مبلغ ثابت';
    }

    /**
     * Get status name in Persian
     */
    public function getStatusNameAttribute()
    {
        return $this->status === 'active' ? 'فعال' : 'غیرفعال';
    }
}

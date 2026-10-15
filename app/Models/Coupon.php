<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'type', 'value', 'max_discount', 'min_order', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'value' => 'integer',
        'max_discount' => 'integer',
        'min_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isValid(): bool
    {
        $today = Carbon::today();

        return $this->status === 'active'
            && (!$this->start_date || !$today->lt($this->start_date))
            && (!$this->end_date || !$today->gt($this->end_date));
    }

    public function calculateDiscount(int $orderAmount): int
    {
        if ($orderAmount < (int) $this->min_order) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = (int) floor($orderAmount * ((int) $this->value / 100));
            if ($this->max_discount !== null) {
                $discount = min($discount, (int) $this->max_discount);
            }
            return min($discount, $orderAmount);
        }

        return min((int) $this->value, $orderAmount);
    }
}

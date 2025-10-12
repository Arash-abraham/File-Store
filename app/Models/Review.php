<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'body', 'status'];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helpfuls()
    {
        return $this->hasMany(ReviewHelpful::class);
    }

    public function reports()
    {
        return $this->hasMany(ReviewReport::class);
    }

    public function getHelpfulCountAttribute()
    {
        return $this->helpfuls()->count();
    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count();
    }

    public function hasBeenHelpfulBy($userId)
    {
        return $this->helpfuls()->where('user_id', $userId)->exists();
    }

    public function hasBeenReportedBy($userId)
    {
        return $this->reports()->where('user_id', $userId)->exists();
    }
}
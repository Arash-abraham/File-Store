<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A row in `reviews` is one of:
 *  - a comment (type=comment, parent_id=null, rating optional)
 *  - a product question (type=question, parent_id=null)
 *  - a reply to either of the above (parent_id set; type copied from the parent)
 *
 * Nothing is shown on the storefront until an admin sets status=approved.
 */
class Review extends Model
{
    public const TYPE_COMMENT = 'comment';
    public const TYPE_QUESTION = 'question';

    protected $fillable = ['product_id', 'user_id', 'type', 'parent_id', 'rating', 'body', 'image_path', 'status', 'edited_at'];

    protected $casts = [
        'edited_at' => 'datetime',
        'rating' => 'integer',
    ];

    protected $appends = ['image_url'];

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

    /** Top-level comments (not replies, not questions). Used for rating averages. */
    public function scopeTopLevelComments($query)
    {
        return $query->where('type', self::TYPE_COMMENT)->whereNull('parent_id');
    }

    /** Top-level questions (پرسش و پاسخ). */
    public function scopeQuestions($query)
    {
        return $query->where('type', self::TYPE_QUESTION)->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id');
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

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'is_staff', 'body'];

    protected $casts = ['is_staff' => 'boolean'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function present(): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'is_staff' => (bool) $this->is_staff,
            'author' => $this->is_staff ? 'پشتیبانی فایل‌استور' : ($this->user?->name ?? 'کاربر'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}

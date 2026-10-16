<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'subject', 'status', 'message', 'response', 'assigned_to', 'response_time'];

    protected $casts = ['response_time' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->orderBy('id');
    }

    /** Shape shared by the customer and admin screens. Messages are included only when loaded. */
    public function present(): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'user' => $this->relationLoaded('user') && $this->user
                ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email]
                : null,
            'messages_count' => $this->messages_count ?? ($this->relationLoaded('messages') ? $this->messages->count() : null),
            'messages' => $this->relationLoaded('messages')
                ? $this->messages->map(fn (TicketMessage $message) => $message->present())->values()->all()
                : [],
        ];
    }
}

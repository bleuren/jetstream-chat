<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationParticipant extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'unread_count',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        // Use the configured user model if set, otherwise fall back to the default auth model
        $userModel = config('jetstream-chat.user_model') ?: config('auth.providers.users.model');

        return $this->belongsTo($userModel);
    }

    /**
     * Mark conversation as read for this participant.
     */
    public function markAsRead()
    {
        $this->update([
            'last_read_at' => now(),
            'unread_count' => 0,
        ]);
    }

    /**
     * Increment unread count for this participant.
     */
    public function incrementUnread()
    {
        $this->increment('unread_count');
    }
}

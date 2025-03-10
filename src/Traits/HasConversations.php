<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasConversations
{
    /**
     * Get the conversations for the user.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class, 'user_id');
    }

    /**
     * Get the total count of unread messages for the user.
     */
    public function unreadMessagesCount(): int
    {
        return $this->conversations()->sum('unread_count');
    }
}

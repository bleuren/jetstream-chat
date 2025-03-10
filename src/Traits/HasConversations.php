<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Models\ConversationParticipant;

trait HasConversations
{
    /**
     * Get the conversations for the user.
     */
    public function conversations()
    {
        return $this->hasMany(ConversationParticipant::class, 'user_id');
    }
}

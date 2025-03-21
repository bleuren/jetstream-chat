<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Bleuren\JetstreamChat\Models\Message;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasConversations
{
    /**
     * 取得使用者的會話
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class, 'user_id');
    }

    /**
     * 取得使用者的訊息
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    /**
     * 取得使用者未讀訊息總數
     */
    public function unreadMessagesCount(): int
    {
        return $this->conversations()->sum('unread_count');
    }
}

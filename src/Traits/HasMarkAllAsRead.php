<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Events\ConversationRead;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;

trait HasMarkAllAsRead
{
    public function markAllAsRead()
    {
        $userId = Auth::id();

        // Get all conversations with unread messages
        $participants = ConversationParticipant::where('user_id', $userId)
            ->where('unread_count', '>', 0)
            ->get();

        // Mark each as read and dispatch individual events
        foreach ($participants as $participant) {
            $conversationId = $participant->conversation_id;
            $participant->markAsRead();

            // Dispatch both Livewire and broadcasting events
            $this->dispatch('conversation-read', conversationId: $conversationId);
            ConversationRead::dispatch($conversationId, $userId);
        }

        // Update UI
        $this->dispatch('refresh-unread-count');
        $this->dispatch('refresh-chat-list');
    }
}

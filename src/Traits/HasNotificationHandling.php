<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Events\ConversationRead;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

trait HasNotificationHandling
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        $userId = Auth::id();

        return [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'updateUnreadCount',
            "echo-private:App.Models.User.{$userId},.MessageCreated" => 'updateUnreadCount',
            "echo-private:App.Models.User.{$userId},.ConversationRead" => 'updateUnreadCount',
        ];
    }

    #[On('conversation-read')]
    #[On('refresh-unread-count')]
    public function updateUnreadCount()
    {
        $user = Auth::user();
        $this->unreadCount = $user->unreadMessagesCount();
    }

    /**
     * 標記所有會話為已讀
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();

        // 取得所有有未讀訊息的會話
        $participants = ConversationParticipant::where('user_id', $userId)
            ->where('unread_count', '>', 0)
            ->get();

        // 標記每個為已讀並發送各個事件
        foreach ($participants as $participant) {
            $conversationId = $participant->conversation_id;
            $participant->markAsRead();

            // 發送 Livewire 和廣播事件
            $this->dispatch('conversation-read', conversationId: $conversationId);
            ConversationRead::dispatch($conversationId, $userId);
        }

        // 更新 UI
        $this->dispatch('refresh-unread-count');
        $this->dispatch('refresh-chat-list');
    }
}

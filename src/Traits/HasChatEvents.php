<?php

namespace Bleuren\JetstreamChat\Traits;

use Bleuren\JetstreamChat\Events\ConversationRead;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;

trait HasChatEvents
{
    /**
     * 取得基本事件監聽器配置
     */
    protected function getBaseListeners(): array
    {
        $userId = Auth::id();

        return [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'handleConversationCreated',
            "echo-private:App.Models.User.{$userId},.MessageCreated" => 'handleMessageCreated',
            "echo-private:App.Models.User.{$userId},.ConversationRead" => 'handleConversationRead',
        ];
    }

    /**
     * 處理會話創建事件
     */
    public function handleConversationCreated()
    {
        $this->refreshComponent();
    }

    /**
     * 處理訊息創建事件
     */
    public function handleMessageCreated()
    {
        $this->refreshComponent();
    }

    /**
     * 處理會話已讀事件
     */
    public function handleConversationRead($conversationId = null)
    {
        $this->refreshComponent();
    }

    /**
     * 重新整理元件
     */
    protected function refreshComponent()
    {
        // 子類別可自訂實現
    }

    /**
     * 加入Echo頻道
     */
    protected function joinConversationChannel($conversationId)
    {
        $channelName = "App.Models.Conversation.{$conversationId}";
        $this->dispatch('echo-join', $channelName);

        return $channelName;
    }

    /**
     * 離開Echo頻道
     */
    protected function leaveConversationChannel($conversationId)
    {
        $channelName = "App.Models.Conversation.{$conversationId}";
        $this->dispatch('echo-leave', $channelName);

        return $channelName;
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

    /**
     * 標記會話為已讀並通知
     */
    protected function markConversationAsRead($conversationId)
    {
        $participant = $this->findParticipant($conversationId);

        if ($participant) {
            $participant->markAsRead();
            $this->dispatch('conversation-read', conversationId: $conversationId);
            ConversationRead::dispatch($conversationId, Auth::id());
            $this->dispatch('refresh-unread-count');
        }
    }

    /**
     * 查找當前用戶的參與者資料
     */
    protected function findParticipant($conversationId)
    {
        return ConversationParticipant::where([
            'conversation_id' => $conversationId,
            'user_id' => Auth::id(),
        ])->first();
    }
}

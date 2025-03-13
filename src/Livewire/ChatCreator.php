<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Events\ConversationCreated;
use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Bleuren\JetstreamChat\Traits\HasChatEvents;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

abstract class ChatCreator extends Component
{
    use HasChatEvents;

    public $showModal = false;

    /**
     * 開啟模態視窗
     */
    public function openModal()
    {
        $this->showModal = true;
        $this->resetModalData();
    }

    /**
     * 關閉模態視窗
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetModalData();
    }

    /**
     * 重設模態資料
     */
    abstract protected function resetModalData();

    /**
     * 創建新對話
     */
    protected function createConversation(array $data, array $participants)
    {
        $conversation = Conversation::create($data);

        foreach ($participants as $userId) {
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userId,
                'last_read_at' => $userId === Auth::id() ? now() : null,
            ]);
        }

        // 廣播新會話事件
        ConversationCreated::dispatch($conversation);

        // 更新UI
        $this->dispatch('conversation-selected', conversationId: $conversation->id);
        $this->dispatch('conversation-added');
        $this->closeModal();

        return $conversation;
    }
}

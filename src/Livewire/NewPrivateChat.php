<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;

class NewPrivateChat extends ChatCreator
{
    public $searchQuery = '';

    public $searchResults = [];

    protected function resetModalData()
    {
        $this->reset('searchQuery', 'searchResults');
    }

    public function render()
    {
        return view('jetstream-chat::livewire.new-private-chat');
    }

    public function updatedSearchQuery()
    {
        $minChars = config('jetstream-chat.search_min_characters', 2);

        if (strlen($this->searchQuery) >= $minChars) {
            $userModel = config('jetstream-chat.user_model') ?: config('auth.providers.users.model');
            $this->searchResults = $userModel::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', Auth::id())
                ->limit(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function startConversation($userId)
    {
        // 檢查是否已有對話
        $existingParticipations = ConversationParticipant::where('user_id', Auth::id())
            ->pluck('conversation_id');

        $existingConversation = Conversation::whereIn('id', $existingParticipations)
            ->where('type', 'private')
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if ($existingConversation) {
            $this->dispatch('conversation-selected', conversationId: $existingConversation->id);
            $this->closeModal();

            return;
        }

        // 創建新對話
        $this->createConversation(
            ['type' => 'private'],
            [Auth::id(), $userId]
        );
    }
}

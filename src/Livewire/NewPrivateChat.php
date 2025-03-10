<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewPrivateChat extends Component
{
    public $showModal = false;

    public $searchQuery = '';

    public $searchResults = [];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('jetstream-chat::livewire.new-private-chat');
    }

    public function updatedSearchQuery()
    {
        // Use the configured minimum character count for search
        $minChars = config('jetstream-chat.search_min_characters', 2);

        if (strlen($this->searchQuery) >= $minChars) {
            // Use the configured user model if set, otherwise fall back to the default auth model
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

            return;
        }

        $conversation = Conversation::create([
            'type' => 'private',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
        ]);

        $this->dispatch('conversation-selected', conversationId: $conversation->id);
        $this->reset('searchQuery', 'searchResults');
        $this->closeModal();
    }
}

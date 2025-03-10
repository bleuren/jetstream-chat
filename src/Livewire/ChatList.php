<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $activeConversationId = null;

    public function render()
    {
        $user = Auth::user();

        $privateConversationIds = $user->conversations()->pluck('conversation_id');
        $privateConversations = Conversation::where('type', 'private')
            ->whereIn('id', $privateConversationIds)
            ->get();

        $teamIds = $user->allTeams()->pluck('id');
        $teamConversations = Conversation::where('type', 'team')
            ->whereIn('team_id', $teamIds)
            ->get();

        return view('jetstream-chat::livewire.chat-list', [
            'privateConversations' => $privateConversations,
            'teamConversations' => $teamConversations,
        ]);
    }

    public function selectConversation($conversationId)
    {
        $this->activeConversationId = $conversationId;
        $this->dispatch('conversation-selected', conversationId: $conversationId);
    }
}

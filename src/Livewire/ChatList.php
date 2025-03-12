<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $activeConversationId = null;

    protected $listeners = [
        'conversation-added' => '$refresh',
        'refresh-chat-list' => '$refresh',
    ];

    public function getListeners()
    {
        $userId = auth()->id();

        return array_merge($this->listeners, [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'handleConversationCreated',
        ]);
    }

    public function handleConversationCreated()
    {
        $this->dispatch('refresh-unread-count');
    }

    public function render()
    {
        $user = Auth::user();

        $privateConversationIds = $user->conversations()->pluck('conversation_id');
        $privateConversations = Conversation::where('type', 'private')
            ->whereIn('id', $privateConversationIds)
            ->with([
                'latestMessage',
                'currentUserParticipant',
                'otherParticipants.user',
            ])
            ->get();

        $teamIds = $user->allTeams()->pluck('id');
        $teamConversations = Conversation::where('type', 'team')
            ->whereIn('team_id', $teamIds)
            ->with([
                'team',
                'latestMessage',
                'currentUserParticipant',
            ])
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

        // Mark conversation as read when selected
        $conversation = Conversation::find($conversationId);
        if ($conversation) {
            $participant = $conversation->currentUserParticipant;
            if ($participant) {
                $participant->markAsRead();
                $this->dispatch('refresh-unread-count');
            }
        }
    }
}

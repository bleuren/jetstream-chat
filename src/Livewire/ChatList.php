<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatList extends Component
{
    public $activeConversationId = null;

    public function render()
    {
        return view('jetstream-chat::livewire.chat-list', [
            'privateConversations' => $this->getPrivateConversations(),
            'teamConversations' => $this->getTeamConversations(),
        ]);
    }

    public function selectConversation($conversationId)
    {
        $this->activeConversationId = $conversationId;
        $this->dispatch('conversation-selected', conversationId: $conversationId);
        $this->markConversationAsRead($conversationId);
    }

    public function getListeners()
    {
        $userId = auth()->id();

        return [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'handleConversationCreated',
            "echo-private:App.Models.User.{$userId},.MessageCreated" => 'handleMessageCreated',
            "echo-private:App.Models.User.{$userId},.ConversationRead" => 'handleConversationRead',
        ];
    }

    public function handleConversationCreated()
    {
        $this->refreshList();
    }

    public function handleMessageCreated()
    {
        $this->refreshList();
    }

    #[On('conversation-read')]
    public function handleConversationRead($conversationId = null)
    {
        $this->refreshList();
    }

    #[On('conversation-added')]
    #[On('refresh-chat-list')]
    public function refreshList()
    {
        // 觸發組件重新渲染
    }

    private function getPrivateConversations()
    {
        $user = Auth::user();
        $privateConversationIds = $user->conversations()->pluck('conversation_id');

        return Conversation::where('type', 'private')
            ->whereIn('id', $privateConversationIds)
            ->with([
                'latestMessage',
                'currentUserParticipant',
                'otherParticipants.user',
            ])
            ->get();
    }

    private function getTeamConversations()
    {
        $user = Auth::user();
        $teamIds = $user->allTeams()->pluck('id');

        return Conversation::where('type', 'team')
            ->whereIn('team_id', $teamIds)
            ->with([
                'team',
                'latestMessage',
                'currentUserParticipant',
            ])
            ->get();
    }

    private function markConversationAsRead($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        if ($conversation) {
            $participant = $conversation->currentUserParticipant;
            if ($participant) {
                $participant->markAsRead();
                $this->dispatch('conversation-read', conversationId: $conversationId);
            }
        }
    }
}

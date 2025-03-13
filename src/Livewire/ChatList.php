<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ChatList extends ChatComponents
{
    public $activeConversationId = null;

    public function render()
    {
        return view('jetstream-chat::livewire.chat-list', [
            'privateConversations' => $this->getConversations('private'),
            'teamConversations' => $this->getConversations('team'),
        ]);
    }

    public function selectConversation($conversationId)
    {
        $this->activeConversationId = $conversationId;
        $this->dispatch('conversation-selected', conversationId: $conversationId);
        $this->markConversationAsRead($conversationId);
    }

    /**
     * 取得指定類型的會話列表
     */
    private function getConversations($type)
    {
        $userId = Auth::id();
        $query = Conversation::where('type', $type);

        if ($type === 'private') {
            $participantIds = Auth::user()->conversations()->pluck('conversation_id');
            $query->whereIn('id', $participantIds);
        } elseif ($type === 'team') {
            $teamIds = Auth::user()->allTeams()->pluck('id');
            $query->whereIn('team_id', $teamIds);
        }

        return $query->with([
            'latestMessage',
            'currentUserParticipant',
            $type === 'team' ? 'team' : 'otherParticipants.user',
        ])->get();
    }

    /**
     * 處理元件重新整理
     */
    #[On('conversation-added')]
    #[On('refresh-chat-list')]
    protected function refreshComponent()
    {
        // 觸發元件重新渲染
    }
}

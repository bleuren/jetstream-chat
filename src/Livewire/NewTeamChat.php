<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class NewTeamChat extends ChatCreator
{
    public $selectedTeamId = null;

    protected function resetModalData()
    {
        $this->reset('selectedTeamId');
    }

    public function render()
    {
        return view('jetstream-chat::livewire.new-team-chat', [
            'teams' => Auth::user()->allTeams(),
        ]);
    }

    public function createTeamChat()
    {
        $this->validate([
            'selectedTeamId' => 'required|exists:teams,id',
        ]);

        $user = Auth::user();
        $team = Jetstream::newTeamModel()->find($this->selectedTeamId);

        if (! $team || ! $user->belongsToTeam($team)) {
            session()->flash('error', __('jetstream-chat::jetstream-chat.not_team_member'));

            return;
        }

        // 檢查是否已有團隊聊天室
        $existingConversation = Conversation::where('team_id', $this->selectedTeamId)
            ->where('type', 'team')
            ->first();

        if ($existingConversation) {
            $this->dispatch('conversation-selected', conversationId: $existingConversation->id);
            $this->closeModal();

            return;
        }

        // 創建新團隊聊天室
        $this->createConversation(
            [
                'type' => 'team',
                'team_id' => $this->selectedTeamId,
            ],
            $team->users->pluck('id')->toArray()
        );
    }
}

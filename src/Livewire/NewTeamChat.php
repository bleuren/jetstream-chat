<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Events\ConversationCreated;
use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class NewTeamChat extends Component
{
    public $showModal = false;

    public $selectedTeamId = null;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('selectedTeamId');
    }

    public function render()
    {
        $teams = Auth::user()->allTeams();

        return view('jetstream-chat::livewire.new-team-chat', [
            'teams' => $teams,
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

        $existingConversation = Conversation::where('team_id', $this->selectedTeamId)
            ->where('type', 'team')
            ->first();

        if ($existingConversation) {
            $this->dispatch('conversation-selected', conversationId: $existingConversation->id);
            $this->closeModal();

            return;
        }

        $conversation = Conversation::create([
            'type' => 'team',
            'team_id' => $this->selectedTeamId,
        ]);

        // Add all team members as participants
        foreach ($team->users as $teamUser) {
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $teamUser->id,
                'last_read_at' => $teamUser->id === $user->id ? now() : null,
            ]);
        }

        // Broadcast the new conversation to all team members
        ConversationCreated::dispatch($conversation);

        $this->dispatch('conversation-selected', conversationId: $conversation->id);
        $this->dispatch('conversation-added');
        $this->closeModal();
    }
}

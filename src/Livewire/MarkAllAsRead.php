<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class MarkAllAsRead extends Component
{
    public function render()
    {
        return view('jetstream-chat::livewire.mark-all-as-read');
    }

    #[On('mark-all-as-read')]
    public function markAllAsRead()
    {
        ConversationParticipant::where('user_id', Auth::id())
            ->update([
                'last_read_at' => now(),
                'unread_count' => 0,
            ]);
        $this->dispatch('refresh-unread-count');
        $this->dispatch('refresh-chat-list');
    }
}

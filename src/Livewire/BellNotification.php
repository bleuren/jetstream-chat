<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Traits\HasMarkAllAsRead;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class BellNotification extends Component
{
    use HasMarkAllAsRead;

    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        $userId = Auth::id();

        return [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'updateUnreadCount',
            "echo-private:App.Models.User.{$userId},.MessageCreated" => 'updateUnreadCount',
            "echo-private:App.Models.User.{$userId},.ConversationRead" => 'updateUnreadCount',
        ];
    }

    #[On('conversation-read')]
    #[On('refresh-unread-count')]
    public function updateUnreadCount()
    {
        $user = Auth::user();
        $this->unreadCount = $user->unreadMessagesCount();
    }

    public function render()
    {
        return view('jetstream-chat::livewire.bell-notification');
    }
}

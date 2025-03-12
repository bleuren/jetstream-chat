<?php

namespace Bleuren\JetstreamChat\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class BellNotification extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        $userId = auth()->id();

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
        $user = auth()->user();
        $this->unreadCount = $user->unreadMessagesCount();
    }

    public function markAllAsRead()
    {
        $this->dispatch('mark-all-as-read');
    }

    public function render()
    {
        return view('jetstream-chat::livewire.bell-notification');
    }
}

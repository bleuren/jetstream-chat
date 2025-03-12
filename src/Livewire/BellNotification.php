<?php

namespace Bleuren\JetstreamChat\Livewire;

use Livewire\Component;

class BellNotification extends Component
{
    public $unreadCount = 0;

    protected $listeners = ['refresh-unread-count' => 'updateUnreadCount'];

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        $userId = auth()->id();

        return array_merge($this->listeners, [
            "echo-private:App.Models.User.{$userId},.ConversationCreated" => 'updateUnreadCount',
            "echo-private:App.Models.User.{$userId},.MessageCreated" => 'updateUnreadCount',
        ]);
    }

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

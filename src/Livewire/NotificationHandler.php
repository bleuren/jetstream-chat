<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Traits\HasChatEvents;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationHandler extends Component
{
    use HasChatEvents;

    public $unreadCount = 0;

    public $renderAsBell = false;

    public function mount($renderAsBell = false)
    {
        $this->renderAsBell = $renderAsBell;
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        return $this->getBaseListeners();
    }

    #[On('conversation-read')]
    #[On('refresh-unread-count')]
    public function updateUnreadCount()
    {
        $this->unreadCount = Auth::user()->unreadMessagesCount();
    }

    /**
     * 根據是否用於鈴鐺通知選擇渲染視圖
     */
    public function render()
    {
        return $this->renderAsBell
            ? view('jetstream-chat::livewire.bell-notification')
            : view('jetstream-chat::livewire.mark-all-as-read');
    }
}

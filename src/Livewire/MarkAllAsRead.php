<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Traits\HasNotificationHandling;
use Livewire\Attributes\On;
use Livewire\Component;

class MarkAllAsRead extends Component
{
    use HasNotificationHandling;

    #[On('mark-all-as-read')]
    public function handleMarkAllAsRead()
    {
        $this->markAllAsRead();
    }

    public function render()
    {
        return view('jetstream-chat::livewire.mark-all-as-read');
    }
}

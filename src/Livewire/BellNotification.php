<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Traits\HasNotificationHandling;
use Livewire\Component;

class BellNotification extends Component
{
    use HasNotificationHandling;

    public function render()
    {
        return view('jetstream-chat::livewire.bell-notification');
    }
}

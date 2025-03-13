<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Traits\HasChatEvents;
use Livewire\Component;

abstract class ChatComponents extends Component
{
    use HasChatEvents;

    /**
     * 取得所有事件監聽器
     */
    public function getListeners()
    {
        return $this->getBaseListeners();
    }

    /**
     * 重新整理元件，子類別可覆寫特定邏輯
     */
    public function refreshComponent()
    {
        // 預設實作為空
    }
}

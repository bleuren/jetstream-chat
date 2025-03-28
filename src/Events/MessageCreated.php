<?php

namespace Bleuren\JetstreamChat\Events;

use Bleuren\JetstreamChat\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 創建新事件實例
     */
    public function __construct(public Message $message)
    {
        // 更新除發送者外所有參與者的未讀計數
        $conversation = $message->conversation;
        $participants = $conversation->participants()
            ->where('user_id', '!=', $message->user_id)
            ->get();

        foreach ($participants as $participant) {
            $participant->incrementUnread();
        }
    }

    /**
     * 獲取事件應該廣播的頻道
     */
    public function broadcastOn(): array
    {
        $channels = [];

        // 為每個參與者添加頻道 (除發送者外)
        foreach ($this->message->conversation->otherParticipants as $participant) {
            $channels[] = new PrivateChannel('App.Models.User.'.$participant->user_id);
        }

        // 為會話添加頻道
        $channels[] = new PrivateChannel('App.Models.Conversation.'.$this->message->conversation_id);

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'MessageCreated';
    }
}

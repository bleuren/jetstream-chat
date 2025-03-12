<?php

namespace Bleuren\JetstreamChat\Events;

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Conversation $conversation) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        foreach ($this->conversation->participants as $participant) {
            $channels[] = new PrivateChannel('App.Models.User.'.$participant->user_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'ConversationCreated';
    }
}

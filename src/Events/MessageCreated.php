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
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        // Update unread count for all participants except sender
        $conversation = $message->conversation;
        $participants = $conversation->participants()
            ->where('user_id', '!=', $message->user_id)
            ->get();

        foreach ($participants as $participant) {
            $participant->incrementUnread();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.Conversation.'.$this->message->conversation_id),
        ];
    }
}

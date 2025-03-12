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
        $channels = [];

        // Add channel for each participant (except sender)
        foreach ($this->message->conversation->otherParticipants as $participant) {
            $channels[] = new PrivateChannel('App.Models.User.'.$participant->user_id);
        }

        // Add channel for the conversation
        $channels[] = new PrivateChannel('App.Models.Conversation.'.$this->message->conversation_id);

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'MessageCreated';
    }
}

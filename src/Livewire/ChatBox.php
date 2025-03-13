<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Events\MessageCreated;
use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ChatBox extends ChatComponents
{
    public $conversationId = null;

    public $messageText = '';

    public function mount()
    {
        if ($this->conversationId) {
            $this->markConversationAsRead($this->conversationId);
        }
    }

    public function render()
    {
        $data = [
            'messages' => collect(),
            'conversation' => null,
            'participants' => collect(),
        ];

        if ($this->conversationId) {
            $conversation = Conversation::with('team')->find($this->conversationId);

            if ($conversation) {
                $perPage = config('jetstream-chat.messages_per_page', 50);

                $data = [
                    'messages' => $conversation->messages()
                        ->with('user')
                        ->latest()
                        ->limit($perPage)
                        ->get(),
                    'conversation' => $conversation,
                    'participants' => $conversation->type == 'private'
                        ? $conversation->participants()->with('user')->get()->pluck('user')
                        : collect(),
                ];
            }
        }

        return view('jetstream-chat::livewire.chat-box', $data);
    }

    #[On('conversation-selected')]
    public function loadConversation($conversationId)
    {
        // 離開舊的聊天頻道
        if ($this->conversationId) {
            $this->leaveConversationChannel($this->conversationId);
        }

        $this->conversationId = $conversationId;

        // 加入新的聊天頻道
        if ($this->conversationId) {
            $this->joinConversationChannel($this->conversationId);
            $this->markConversationAsRead($this->conversationId);
        }
    }

    #[On('message-received')]
    public function handleNewMessage()
    {
        $this->dispatch('messages-updated');

        if ($this->conversationId) {
            $this->markConversationAsRead($this->conversationId);
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'messageText' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => Auth::id(),
            'body' => $this->messageText,
        ]);

        $this->reset('messageText');

        $this->dispatch('messages-updated');
        MessageCreated::dispatch($message);
    }
}

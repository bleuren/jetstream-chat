<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Models\Conversation;
use Bleuren\JetstreamChat\Models\ConversationParticipant;
use Bleuren\JetstreamChat\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatBox extends Component
{
    public $conversationId = null;

    public $messageText = '';

    #[On('conversation-selected')]
    public function loadConversation($conversationId)
    {
        // If there was a previous conversation subscription, unsubscribe
        if ($this->conversationId) {
            $this->dispatch('echo-leave', "App.Models.Conversation.{$this->conversationId}");
        }

        $this->conversationId = $conversationId;

        // Subscribe to the new conversation channel
        if ($this->conversationId) {
            $this->dispatch('echo-join', "App.Models.Conversation.{$this->conversationId}");
            $this->markAsRead();
        }
    }

    public function mount()
    {
        if ($this->conversationId) {
            $this->markAsRead();
        }
    }

    protected function markAsRead()
    {
        if ($this->conversationId) {
            $participant = ConversationParticipant::where([
                'conversation_id' => $this->conversationId,
                'user_id' => Auth::id(),
            ])->first();

            if ($participant) {
                $participant->markAsRead();
                $this->dispatch('refresh-unread-count');
            }
        }
    }

    public function render()
    {
        $messages = [];
        $conversation = null;
        $participants = [];

        if ($this->conversationId) {
            $conversation = Conversation::find($this->conversationId);

            // Use the configured messages_per_page setting
            $perPage = config('jetstream-chat.messages_per_page', 50);
            $messages = $conversation->messages()
                ->with('user')
                ->latest()
                ->limit($perPage)
                ->get();

            if ($conversation->type == 'private') {
                $participants = $conversation->participants()
                    ->with('user')
                    ->get()
                    ->pluck('user');
            }
        }

        return view('jetstream-chat::livewire.chat-box', [
            'messages' => $messages,
            'conversation' => $conversation,
            'participants' => $participants,
        ]);
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
        $this->dispatch('messagesUpdated');
    }

    public function getListeners()
    {
        $listeners = [
            'conversation-selected' => 'loadConversation',
            'message-received' => 'handleNewMessage',
        ];

        if ($this->conversationId) {
            $listeners["echo-private:App.Models.Conversation.{$this->conversationId},.MessageCreated"] = 'handleNewMessage';
        }

        return $listeners;
    }

    public function handleNewMessage()
    {
        $this->dispatch('messagesUpdated');
        $this->dispatch('refresh-unread-count');
    }

    public function markAllAsRead()
    {
        if ($this->conversationId) {
            $this->markAsRead();
        }
    }
}

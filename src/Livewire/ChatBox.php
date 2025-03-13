<?php

namespace Bleuren\JetstreamChat\Livewire;

use Bleuren\JetstreamChat\Events\ConversationRead;
use Bleuren\JetstreamChat\Events\MessageCreated;
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

    public function mount()
    {
        if ($this->conversationId) {
            $this->markAsRead();
        }
    }

    public function render()
    {
        $messages = [];
        $conversation = null;
        $participants = [];

        if ($this->conversationId) {
            $conversation = Conversation::find($this->conversationId);

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

    #[On('conversation-selected')]
    public function loadConversation($conversationId)
    {
        if ($this->conversationId) {
            $this->dispatch('echo-leave', "App.Models.Conversation.{$this->conversationId}");
        }

        $this->conversationId = $conversationId;

        if ($this->conversationId) {
            $this->dispatch('echo-join', "App.Models.Conversation.{$this->conversationId}");
            $this->markAsRead();
        }
    }

    #[On('message-received')]
    public function handleNewMessage()
    {
        $this->dispatch('messages-updated');

        if ($this->conversationId) {
            $this->markAsRead();
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

    public function markAsRead()
    {
        if ($this->conversationId) {
            $participant = ConversationParticipant::where([
                'conversation_id' => $this->conversationId,
                'user_id' => Auth::id(),
            ])->first();

            if ($participant) {
                $participant->markAsRead();
                $this->dispatch('conversation-read', conversationId: $this->conversationId);
                ConversationRead::dispatch($this->conversationId, Auth::id());
            }
        }
    }
}

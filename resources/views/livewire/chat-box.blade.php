<div class="flex flex-col h-full">
    @if($conversation)
    <div class="flex-none px-4 py-3 border-b dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($conversation->type == 'team')
                    <div class="w-10 h-10 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-lg font-medium">{{ substr($conversation->team->name, 0, 1) }}</span>
                    </div>
                    @else
                    @php
                    $otherParticipant = $conversation->participants->where('user_id', '!=', auth()->id())->first();
                    $otherUser = $otherParticipant ? $otherParticipant->user : null;
                    @endphp
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="w-10 h-10 rounded-full object-cover" src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}">
                    @else
                    <div class="w-10 h-10 bg-gray-500 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-lg font-medium">{{ $otherUser ? substr($otherUser->name, 0, 1) : '?' }}</span>
                    </div>
                    @endif
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        @if($conversation->type == 'team')
                        {{ $conversation->team->name }}
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ __('jetstream-chat::jetstream-chat.team_chat_room') }}</span>
                        @else
                        {{ $otherUser ? $otherUser->name : __('jetstream-chat::jetstream-chat.unknown_user') }}
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ __('jetstream-chat::jetstream-chat.private_conversation') }}</span>
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900 min-h-0 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600"
        id="chat-messages"
        x-data="{ 
            scrollToBottom() {
                this.$el.scrollTop = this.$el.scrollHeight;
            }
        }"
        x-init="
            scrollToBottom();
            Livewire.on('messagesUpdated', () => {
                $nextTick(() => scrollToBottom());
            });
            Livewire.on('message-received', () => {
                $nextTick(() => scrollToBottom());
            });
        "
        wire:loading.class="opacity-50">
        @foreach($messages->sortBy('created_at') as $message)
        <div class="flex @if($message->user_id === auth()->id()) justify-end @else justify-start @endif">
            <div class="flex @if($message->user_id === auth()->id()) flex-row-reverse @endif items-start max-w-[70%] group">
                <div class="flex-shrink-0 @if($message->user_id === auth()->id()) ml-2 @else mr-2 @endif">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="w-8 h-8 rounded-full object-cover" src="{{ $message->user->profile_photo_url }}" alt="{{ $message->user->name }}">
                    @else
                    <div class="w-8 h-8 bg-gray-400 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr($message->user->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col">
                    <div class="@if($message->user_id === auth()->id())
                        bg-blue-500 text-white rounded-2xl rounded-tr-none
                        @else
                        bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-2xl rounded-tl-none
                        @endif
                        px-4 py-2 shadow-sm">
                        <p class="text-sm">{{ $message->body }}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 @if($message->user_id === auth()->id()) text-right @endif">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex-none sticky bottom-0 p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 z-10">
        <form wire:submit.prevent="sendMessage" class="flex space-x-2">
            <x-input
                type="text"
                wire:model="messageText"
                class="flex-1 rounded-full"
                placeholder="{{ __('jetstream-chat::jetstream-chat.type_message') }}" />
            <x-button>
                {{ __('jetstream-chat::jetstream-chat.send') }}
            </x-button>
        </form>
    </div>
    @else
    <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="mt-2 text-sm">{{ __('jetstream-chat::jetstream-chat.select_conversation') }}</p>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('echo-join', (channelName) => {
            window.Echo.private(channelName)
                .listen('.MessageCreated', () => {
                    Livewire.dispatch('message-received');
                });
        });

        Livewire.on('echo-leave', (channelName) => {
            window.Echo.leave(channelName);
        });
    });
</script>
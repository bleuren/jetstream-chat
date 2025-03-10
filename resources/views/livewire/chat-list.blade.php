<div class="p-4">
    <div class="space-y-6">
        <div>
            <h3 class="px-2 text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('jetstream-chat::jetstream-chat.team_chats') }}</h3>
            <ul class="mt-2 space-y-1">
                @foreach($teamConversations as $conversation)
                @php
                $unreadCount = $conversation->currentUserParticipant ? $conversation->currentUserParticipant->unread_count : 0;
                @endphp
                <li>
                    <button
                        wire:click="selectConversation({{ $conversation->id }})"
                        class="w-full text-left px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                                @if($activeConversationId == $conversation->id) 
                                    bg-blue-100 text-blue-900 dark:bg-blue-900/50 dark:text-blue-100
                                @else 
                                    hover:bg-gray-100 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300
                                @endif">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">{{ substr($conversation->team->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3 flex-grow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">{{ $conversation->team->name }}</p>
                                    @if($unreadCount > 0)
                                    <span class="ml-auto bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100 text-xs font-medium px-2 py-0.5 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($conversation->latestMessage)
                                    {{ $conversation->latestMessage->body }}
                                    @else
                                    {{ __('jetstream-chat::jetstream-chat.team_chat_room') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="px-2 text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('jetstream-chat::jetstream-chat.private_chats') }}</h3>
            <ul class="mt-2 space-y-1">
                @foreach($privateConversations as $conversation)
                @php
                $unreadCount = $conversation->currentUserParticipant ? $conversation->currentUserParticipant->unread_count : 0;
                $otherParticipant = $conversation->otherParticipants->first();
                $otherUser = $otherParticipant ? $otherParticipant->user : null;
                @endphp
                <li>
                    <button
                        wire:click="selectConversation({{ $conversation->id }})"
                        class="w-full text-left px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                                @if($activeConversationId == $conversation->id) 
                                    bg-blue-100 text-blue-900 dark:bg-blue-900/50 dark:text-blue-100
                                @else 
                                    hover:bg-gray-100 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300
                                @endif">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-500 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">{{ $otherUser ? substr($otherUser->name, 0, 1) : '?' }}</span>
                            </div>
                            <div class="ml-3 flex-grow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">{{ $otherUser ? $otherUser->name : __('jetstream-chat::jetstream-chat.unknown_user') }}</p>
                                    @if($unreadCount > 0)
                                    <span class="ml-auto bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100 text-xs font-medium px-2 py-0.5 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($conversation->latestMessage)
                                    {{ $conversation->latestMessage->body }}
                                    @else
                                    {{ __('jetstream-chat::jetstream-chat.private_conversation') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
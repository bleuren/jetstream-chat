<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        <x-heroicon-o-bell class="w-6 h-6" />
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <div x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-lg py-2"
        style="display: none;">
        <div class="px-4 py-2 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ __('jetstream-chat::jetstream-chat.notifications') }}</h3>

            @if($unreadCount > 0)
            <x-button
                wire:click="markAllAsRead"
                class="text-xs px-2 py-1 h-7"
                title="{{ __('jetstream-chat::jetstream-chat.mark_all_read') }}">
                {{ __('jetstream-chat::jetstream-chat.mark_all_read') }}
            </x-button>
            @endif
        </div>

        @if($unreadCount > 0)
        <div class="max-h-60 overflow-y-auto">
            <a href="{{ route('jetstream-chat') }}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ __('jetstream-chat::jetstream-chat.unread_messages', ['count' => $unreadCount]) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('jetstream-chat::jetstream-chat.check_conversations') }}
                </p>
            </a>
        </div>
        @else
        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
            {{ __('jetstream-chat::jetstream-chat.no_new_notifications') }}
        </div>
        @endif
    </div>
</div>
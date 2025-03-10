<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
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
        <div class="px-4 py-2 border-b dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ __('jetstream-chat::jetstream-chat.notifications') }}</h3>
        </div>

        @if($unreadCount > 0)
        <div class="max-h-60 overflow-y-auto">
            <x-button
                wire:click="$dispatch('mark-all-read')"
                class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 px-4 py-2">
                {{ __('jetstream-chat::jetstream-chat.mark_all_read') }}
            </x-button>

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
<button
    wire:click="markAllAsRead"
    class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-colors duration-200 ease-in-out"
    title="{{ __('jetstream-chat::jetstream-chat.mark_all_read') }}">
    <x-heroicon-o-check class="w-5 h-5" />
</button>
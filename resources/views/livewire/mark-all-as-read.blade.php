<x-button
    wire:click="markAllAsRead"
    class="flex items-center">
    <x-heroicon-s-check class="w-4 h-4 mr-2" />
    {{ __('jetstream-chat::jetstream-chat.mark_all_read') }}
</x-button>
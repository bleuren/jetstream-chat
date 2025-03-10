<x-button
    wire:click="markAllAsRead"
    class="flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
    </svg>
    {{ __('jetstream-chat::jetstream-chat.mark_all_read') }}
</x-button>
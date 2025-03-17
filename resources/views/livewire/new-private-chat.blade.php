<div>
    <button
        wire:click="openModal"
        class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-colors duration-200 ease-in-out"
        title="{{ __('jetstream-chat::jetstream-chat.new_private_chat') }}">
        <x-heroicon-o-user-plus class="w-5 h-5" />
    </button>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            <h2 class="text-lg font-medium">{{ __('jetstream-chat::jetstream-chat.new_private_chat_title') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ __('jetstream-chat::jetstream-chat.new_private_chat_subtitle') }}</p>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="relative">
                    <x-input
                        type="text"
                        wire:model.live="searchQuery"
                        class="w-full pr-10"
                        placeholder="{{ __('jetstream-chat::jetstream-chat.search_users') }}" />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400" />
                    </div>
                </div>

                <div class="space-y-1 max-h-60 overflow-y-auto">
                    @foreach($searchResults as $user)
                    <button
                        wire:click="startConversation({{ $user->id }})"
                        class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 ease-in-out">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-500 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-secondary-button wire:click="closeModal">
                    {{ __('jetstream-chat::jetstream-chat.cancel') }}
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
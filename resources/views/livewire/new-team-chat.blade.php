<div>
    <button
        wire:click="openModal"
        class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-colors duration-200 ease-in-out"
        title="{{ __('jetstream-chat::jetstream-chat.new_team_chat') }}">
        <x-heroicon-o-user-group class="w-5 h-5" />
    </button>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            <h2 class="text-lg font-medium">{{ __('jetstream-chat::jetstream-chat.new_team_chat_title') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ __('jetstream-chat::jetstream-chat.new_team_chat_subtitle') }}</p>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <select
                    wire:model="selectedTeamId"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600">
                    <option value="">{{ __('jetstream-chat::jetstream-chat.select_team') }}</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-secondary-button wire:click="closeModal">
                    {{ __('jetstream-chat::jetstream-chat.cancel') }}
                </x-secondary-button>
                <x-button wire:click="createTeamChat">
                    {{ __('jetstream-chat::jetstream-chat.create') }}
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
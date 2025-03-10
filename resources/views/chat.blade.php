<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('jetstream-chat::jetstream-chat.title') }}
            </h2>
            <div>
                <livewire:bell-notification />
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-9rem)] py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-xl border dark:border-gray-700 h-full">
                <div class="grid grid-cols-1 md:grid-cols-3 h-full">
                    <!-- Conversation List -->
                    <div class="col-span-1 border-r dark:border-gray-700 bg-gray-50 dark:bg-gray-900 h-full overflow-hidden">
                        <div class="h-full flex flex-col">
                            <div class="flex-none p-4 space-y-3 border-b dark:border-gray-700 bg-white dark:bg-gray-800">
                                <div class="flex space-x-2">
                                    <livewire:new-private-chat />
                                    <livewire:new-team-chat />
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                                <livewire:chat-list />
                            </div>
                        </div>
                    </div>

                    <!-- Chat Window -->
                    <div class="md:col-span-2 bg-white dark:bg-gray-800 h-full overflow-hidden">
                        <livewire:chat-box />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
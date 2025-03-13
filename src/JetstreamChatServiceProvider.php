<?php

namespace Bleuren\JetstreamChat;

use Bleuren\JetstreamChat\Livewire\BellNotification;
use Bleuren\JetstreamChat\Livewire\ChatBox;
use Bleuren\JetstreamChat\Livewire\ChatList;
use Bleuren\JetstreamChat\Livewire\MarkAllAsRead;
use Bleuren\JetstreamChat\Livewire\NewPrivateChat;
use Bleuren\JetstreamChat\Livewire\NewTeamChat;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class JetstreamChatServiceProvider extends ServiceProvider
{
    /**
     * 註冊服務
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/jetstream-chat.php',
            'jetstream-chat'
        );
    }

    /**
     * 引導服務
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/channels.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jetstream-chat');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'jetstream-chat');

        $this->registerLivewireComponents();
        $this->registerPublishableResources();
    }

    /**
     * 註冊Livewire元件
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('chat-box', ChatBox::class);
        Livewire::component('chat-list', ChatList::class);
        Livewire::component('new-private-chat', NewPrivateChat::class);
        Livewire::component('new-team-chat', NewTeamChat::class);

        // 分別註冊通知相關元件
        Livewire::component('bell-notification', BellNotification::class);
        Livewire::component('mark-all-as-read', MarkAllAsRead::class);
    }

    /**
     * 註冊可發佈資源
     */
    protected function registerPublishableResources(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/jetstream-chat.php' => config_path('jetstream-chat.php'),
        ], 'jetstream-chat-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/jetstream-chat'),
        ], 'jetstream-chat-views');

        $this->publishes([
            __DIR__.'/../lang' => lang_path('vendor/jetstream-chat'),
        ], 'jetstream-chat-lang');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'jetstream-chat-migrations');
    }
}

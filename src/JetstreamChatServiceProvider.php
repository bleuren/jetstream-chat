<?php

namespace Bleuren\JetstreamChat;

use Bleuren\JetstreamChat\Livewire\ChatBox;
use Bleuren\JetstreamChat\Livewire\ChatList;
use Bleuren\JetstreamChat\Livewire\NewPrivateChat;
use Bleuren\JetstreamChat\Livewire\NewTeamChat;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * @property-read Application $app
 */
class JetstreamChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/jetstream-chat.php',
            'jetstream-chat'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerPublishing();

        // Register Livewire components
        $this->registerLivewireComponents();
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/channels.php');
    }

    /**
     * Register the package resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jetstream-chat');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'jetstream-chat');
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__.'/../config/jetstream-chat.php' => config_path('jetstream-chat.php'),
        ], 'jetstream-chat-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/jetstream-chat'),
        ], 'jetstream-chat-views');

        // Publish translations
        $this->publishes([
            __DIR__.'/../lang' => lang_path('vendor/jetstream-chat'),
        ], 'jetstream-chat-lang');

        // Publish migrations
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'jetstream-chat-migrations');
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('chat-box', ChatBox::class);
        Livewire::component('chat-list', ChatList::class);
        Livewire::component('new-private-chat', NewPrivateChat::class);
        Livewire::component('new-team-chat', NewTeamChat::class);
    }
}

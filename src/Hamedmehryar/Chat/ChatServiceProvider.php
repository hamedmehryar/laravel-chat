<?php namespace Hamedmehryar\Chat;

use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            base_path('vendor/hamedmehryar/laravel-chat/src/config/config.php') => config_path('chat.php'),
            base_path('vendor/hamedmehryar/laravel-chat/src/migrations') => base_path('database/migrations'),
            base_path('vendor/hamedmehryar/laravel-chat/src/chat-assets') => base_path('public/hamedmehryar/chat')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            base_path('vendor/hamedmehryar/laravel-chat/src/config/config.php'), 'chat'
        );
    }
}

# Laravel Jetstream Chat

A complete chat solution for Laravel applications using Jetstream, Livewire and Alpine.js. This package provides functionality for both team chat rooms and private conversations.

## Features

- 🔒 Seamless integration with Laravel Jetstream authentication
- 👥 Team chat rooms for team collaboration
- 💬 Private conversations between users
- 🔄 Real-time message updates with Laravel Echo/Reverb
- 🎨 Fully responsive design with Tailwind CSS
- 🌐 Multilingual support (English and Traditional Chinese included)
- 🛠️ Customizable components and views

## Requirements

- PHP 8.2+
- Laravel 12.0+
- Jetstream 5.0+ with Livewire stack
- Livewire 3.0+
- Laravel Echo and Reverb/Pusher for real-time functionality

## Installation

### 1. Install the package via Composer

```bash
composer require bleuren/jetstream-chat
```

### 2. Publish and run the migrations

```bash
php artisan vendor:publish --provider="Bleuren\JetstreamChat\JetstreamChatServiceProvider" --tag="jetstream-chat-migrations"
php artisan migrate
```

### 3. Add the HasConversations trait to your User model

```php
<?php

namespace App\Models;

use Bleuren\JetstreamChat\Traits\HasConversations;
use Laravel\Jetstream\HasTeams;
// ... other imports

class User extends Authenticatable
{
    use HasConversations;
    use HasTeams;
    // ... other traits
}
```

### 4. Configure Laravel Echo for real-time updates

Make sure you have Laravel Echo set up in your application. In your `resources/js/bootstrap.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher', // or 'reverb' if using Laravel Reverb
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

### 5. (Optional) Publish configuration, views, and translations for customization

```bash
# Publish configuration
php artisan vendor:publish --provider="Bleuren\JetstreamChat\JetstreamChatServiceProvider" --tag="jetstream-chat-config"

# Publish views
php artisan vendor:publish --provider="Bleuren\JetstreamChat\JetstreamChatServiceProvider" --tag="jetstream-chat-views"

# Publish translations
php artisan vendor:publish --provider="Bleuren\JetstreamChat\JetstreamChatServiceProvider" --tag="jetstream-chat-lang"
```

## Usage

### Adding a Chat Link to Your Navigation Menu

Add a link to your navigation menu in `resources/views/navigation-menu.blade.php`:

```html
<x-nav-link href="{{ route('jetstream-chat') }}" :active="request()->routeIs('jetstream-chat')">
    {{ __('Chat') }}
</x-nav-link>
```

### Using Individual Components

You can also use the Livewire components individually in your own views:

```html
<livewire:new-private-chat />
<livewire:new-team-chat />
<livewire:chat-list />
<livewire:chat-box />
```

### Configuration

You can modify the configuration in `config/jetstream-chat.php` after publishing:

```php
<?php

return [
    // The path for the chat page
    'path' => 'chat',

    // Default user model (will use Laravel's default if not set)
    'user_model' => null, // example: \App\Models\User::class

    // Number of messages to load per conversation
    'messages_per_page' => 50,
    
    // Minimum characters for user search
    'search_min_characters' => 2,
];
```

These configuration options allow you to customize:

1. **path** - The URL path where the chat page will be accessible
2. **user_model** - The model class to use for user relationships (defaults to your app's User model)
3. **messages_per_page** - The number of messages to load per conversation
4. **search_min_characters** - Minimum character count before user search activates

## Broadcasting

This package relies on Laravel's broadcasting system for real-time updates. Make sure your broadcasting is configured properly in your `.env` file:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-app-cluster
```

Or if using Reverb:

```
BROADCAST_DRIVER=reverb
REVERB_SERVER_URL=your-reverb-server-url
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
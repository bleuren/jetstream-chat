# Jetstream Chat

[English](README.md) | [繁體中文](README.zh-TW.md)

**Jetstream Chat** is a real-time chat package perfectly integrated with Laravel Jetstream, supporting private conversations and team chat rooms. This package utilizes Livewire, Laravel Broadcasting (Laravel Echo), and Tailwind CSS to provide a modern and easily integrated chat solution.

---

## Table of Contents

- [Description](#description)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Instructions](#usage-instructions)
  - [Accessing the Chat Page](#accessing-the-chat-page)
  - [Starting New Chats](#starting-new-chats)
  - [Real-time Features](#real-time-features)
- [Features](#features)
- [Customization and Extension](#customization-and-extension)
- [Contribution](#contribution)
- [License](#license)
- [Support](#support)
- [References](#references)

---

## Description

**Jetstream Chat** provides a complete chat solution for applications using Laravel Jetstream. It supports:
- Private one-on-one chats: Users can search for other users and create private conversations.
- Team chat rooms: Designed for Jetstream team management, allowing all team members to easily join the same chat room.
- Real-time message notifications: Relies on Laravel Broadcasting and Echo to implement real-time message delivery and unread notification updates.
- Multi-language support: Built-in English and Traditional Chinese translations, no additional configuration required.

---

## Requirements

- **PHP**: ^8.2
- **Laravel Framework**: ^12.0
- **Laravel Jetstream**: ^5.3 (Team support recommended to use team chat rooms)
- **Livewire**: ^3.0
- **Blade UI Kit**: blade-heroicons ^2.6
- **Broadcasting Driver**: Such as Pusher or other Laravel supported drivers (to implement real-time functionality)

---

## Installation

### 1. Install via Composer

From your Laravel project root directory, run:

```bash
composer require bleuren/jetstream-chat
```

### 2. Publish Resources

To customize configurations, views, translations, and database migrations, execute the following commands:

- **Publish database migration files**:
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-migrations"
  ```
- **Publish configuration file** (optional but recommended):
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-config"
  ```
- **Publish views**:
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-views"
  ```
- **Publish translation files**:
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-lang"
  ```

### 3. Run Database Migrations

After publishing the database migration files, execute:

```bash
php artisan migrate
```

This will create the following tables:
- `conversations`: Stores conversation data (type and team association).
- `conversation_participants`: Records conversation participants, unread message counts, and last read time.
- `messages`: Stores the content and sender information for each message.

### 4. Add the `HasConversations` Trait to the User Model

To enable unread message counting and conversation relationships, add the following code to `app/Models/User.php`:

```php
use Bleuren\JetstreamChat\Traits\HasConversations;

class User extends Authenticatable
{
    use HasConversations;
    // Other model settings...
}
```

### 5. Set Up Broadcasting

To implement real-time messages and notifications, ensure you have correctly configured Laravel's broadcasting functionality. If using the Pusher driver, set in `.env`:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

Also, set up Laravel Echo in your frontend JavaScript, for example in `resources/js/bootstrap.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

For details, please refer to [Laravel Broadcasting Documentation](https://laravel.com/docs/12.x/broadcasting) and [Laravel Echo Documentation](https://laravel.com/docs/12.x/broadcasting#client-side-installation).

---

## Configuration

After publishing, you can adjust the following configurations in `config/jetstream-chat.php`:
- **path**: Chat page URL path (default is `chat`).
- **messages_per_page**: Number of messages displayed per page in the chat box (default 50).
- **search_min_characters**: Minimum number of characters to initiate user search (default 2).
- **user_model**: Can specify a custom user model, default uses the Laravel authentication model.

You can also override the package's views and translation files as needed.

---

## Usage Instructions

### Accessing the Chat Page

- The chat page default route is `/chat` (can be changed in the configuration file).
- After users log in, they can see the chat interface, including a conversation list on the left and a chat window on the right.

### Starting New Chats

- **Private Chat**: Click the "New Private" button, and the system will open a modal allowing you to search and select other users for private conversations. If a conversation with that user already exists, it will switch directly.
- **Team Chat**: Click the "New Team Chat" button, select a team you belong to to create a team chat room. Note: This feature requires Jetstream team support.

### Real-time Features

- **Message Sending and Receiving**: Using Livewire and Laravel Echo, the chat window can receive new messages in real-time. When a user sends a message, it is broadcast to other users in the conversation and automatically marked as read.
- **Notifications**: The bell icon at the top of the page displays the number of unread messages. Click on the bell to expand the notification window, which provides a "Mark All as Read" function.

---

## Features

- **Real-time Chat**: Based on Laravel Echo and Broadcasting to implement real-time sending and receiving of messages.
- **Multi-conversation Support**: Supports private conversations and team chat rooms, automatically managing conversation participants and unread message counts.
- **Automatic Read Marking**: When users view chat content, messages are automatically marked as read, and notifications are updated via broadcasting.
- **User Search**: Built-in modal and user search functionality, convenient for initiating new private chats.
- **Multi-language Support**: Provides English and Traditional Chinese translations by default, can be expanded and customized as needed.

---

## Customization and Extension

Jetstream Chat is designed with a focus on high extensibility, you can:

- **Override Views**  
  After publishing, views are located in `resources/views/vendor/jetstream-chat`, you can modify the interface according to your project style.

- **Adjust Configuration**  
  Modify `config/jetstream-chat.php` to adjust chat path, message pagination numbers, search character limits, and other parameters.

- **Customize Translations**  
  Modify language files in `lang/vendor/jetstream-chat`, add or modify translation content.

- **Extend Event Logic**  
  Reference event implementations in the `src/Events` directory (such as `ConversationCreated`, `MessageCreated`, `ConversationRead`), and extend broadcasting or event handling processes as needed.

---

## Contribution

We welcome any form of contribution and feedback!
- For bug reports or feature suggestions, please submit in [GitHub Issues](https://github.com/bleuren/jetstream-chat/issues).
- If you have improvement suggestions, feel free to submit a Pull Request, please follow standard branch management and submission guidelines.

---

## License

Jetstream Chat is licensed under the [MIT License](LICENSE). You are free to use, modify, and distribute this package, please refer to the LICENSE file for details.
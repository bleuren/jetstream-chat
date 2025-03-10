<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bleuren Jetstream Chat Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Bleuren Jetstream Chat package.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Chat Path
    |--------------------------------------------------------------------------
    |
    | This value determines the URL path where the chat page will be accessible.
    | For example, a value of 'chat' will make the chat page available at
    | yourdomain.com/chat.
    |
    */
    'path' => 'chat',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This option controls which user model the chat package will use for
    | relationships. By default, it will use the model defined in your
    | auth configuration (typically App\Models\User), but you can
    | override it here if you need to use a different model.
    |
    */
    'user_model' => null, // example: \App\Models\User::class

    /*
    |--------------------------------------------------------------------------
    | Messages Per Page
    |--------------------------------------------------------------------------
    |
    | This option controls how many messages are loaded per conversation.
    | Increasing this number will show more message history but might
    | impact performance for conversations with many messages.
    |
    */
    'messages_per_page' => 50,

    /*
    |--------------------------------------------------------------------------
    | User Search Minimum Characters
    |--------------------------------------------------------------------------
    |
    | This value determines the minimum number of characters required before
    | the user search begins when creating a new private conversation.
    |
    */
    'search_min_characters' => 2,
];

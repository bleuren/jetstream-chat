<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        // Use the configured user model if set, otherwise fall back to the default auth model
        $userModel = config('jetstream-chat.user_model') ?: config('auth.providers.users.model');

        return $this->belongsTo($userModel);
    }
}

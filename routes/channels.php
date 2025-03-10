<?php

use Bleuren\JetstreamChat\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Conversation.{id}', function ($user, $id) {
    $conversation = Conversation::find($id);

    if (! $conversation) {
        return false;
    }

    if ($conversation->type === 'team') {
        return $user->belongsToTeam($conversation->team);
    }

    return $conversation->participants()->where('user_id', $user->id)->exists();
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Jetstream;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'team_id',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

    public function broadcastChannel()
    {
        return 'App.Models.Conversation.'.$this->id;
    }

    /**
     * Get the latest message of the conversation.
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }
}

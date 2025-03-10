<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function otherParticipants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class)
            ->where('user_id', '!=', auth()->id());
    }

    public function currentUserParticipant(): HasOne
    {
        return $this->hasOne(ConversationParticipant::class)
            ->where('user_id', auth()->id());
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
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest();
    }
}

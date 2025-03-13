<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Jetstream\Jetstream;

class Conversation extends Model
{
    protected $fillable = ['type', 'team_id'];

    // 新增預加載常用關聯
    protected $with = ['latestMessage'];

    // 查詢範圍
    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeTeam($query)
    {
        return $query->where('type', 'team');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

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

    public function broadcastChannel(): string
    {
        return 'App.Models.Conversation.'.$this->id;
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest();
    }
}

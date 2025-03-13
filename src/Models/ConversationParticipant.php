<?php

namespace Bleuren\JetstreamChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationParticipant extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'unread_count',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        // 使用設定檔中的使用者模型或預設的認證模型
        $userModel = config('jetstream-chat.user_model') ?: config('auth.providers.users.model');

        return $this->belongsTo($userModel);
    }

    /**
     * 將會話標記為已讀
     */
    public function markAsRead()
    {
        $this->update([
            'last_read_at' => now(),
            'unread_count' => 0,
        ]);
    }

    /**
     * 增加未讀計數
     */
    public function incrementUnread()
    {
        $this->increment('unread_count');
    }
}

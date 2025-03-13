<?php

return [
    // 聊天頁面路徑
    'path' => 'chat',

    // 預設使用者模型 (如果未設定則使用 Laravel 預設)
    'user_model' => null, // 例如: \App\Models\User::class

    // 每個會話載入的訊息數量
    'messages_per_page' => 50,

    // 使用者搜尋的最小字元數
    'search_min_characters' => 2,
];

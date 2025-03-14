# Jetstream Chat

[English](README.md) | [繁體中文](README.zh-TW.md)

**Jetstream Chat** 是一個與 Laravel Jetstream 完美整合的即時聊天套件，支援私人對話與團隊聊天室。這個套件利用 Livewire、Laravel Broadcasting（Laravel Echo）與 Tailwind CSS，提供一個現代化且易於整合的聊天解決方案。

---

## 目錄

- [描述](#描述)
- [需求](#需求)
- [安裝](#安裝)
- [配置](#配置)
- [使用說明](#使用說明)
  - [訪問聊天頁面](#訪問聊天頁面)
  - [啟動新聊天](#啟動新聊天)
  - [即時功能](#即時功能)
- [功能特性](#功能特性)
- [自定義與擴充](#自定義與擴充)
- [貢獻](#貢獻)
- [許可證](#許可證)
- [支援](#支援)
- [參考資料](#參考資料)

---

## 描述

**Jetstream Chat** 為使用 Laravel Jetstream 的應用程式提供了一個完整的聊天解決方案。它支持：
- 私人一對一聊天：使用者可搜尋其他用戶並建立私人對話。
- 團隊聊天室：針對 Jetstream 團隊管理，讓所有團隊成員輕鬆加入同一聊天室。
- 即時消息通知：依賴 Laravel Broadcasting 與 Echo，實現消息的即時傳送與未讀通知更新。
- 多國語系支持：內建英文與繁體中文翻譯，無需額外設定。

---

## 需求

- **PHP**: ^8.2
- **Laravel Framework**: ^12.0
- **Laravel Jetstream**: ^5.3（團隊支持建議啟用以使用團隊聊天室）
- **Livewire**: ^3.0
- **Blade UI Kit**: blade-heroicons ^2.6
- **廣播驅動**：如 Pusher 或其他 Laravel 支持的驅動（實現實時功能）

---

## 安裝

### 1. 透過 Composer 安裝

在您的 Laravel 專案根目錄執行：

```bash
composer require bleuren/jetstream-chat
```

### 2. 發佈資源

為了能夠自訂配置、視圖、翻譯與資料庫遷移，請依序執行下列命令：

- **發布資料庫遷移文件**：
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-migrations"
  ```
- **發布配置文件**（可選但推薦）：
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-config"
  ```
- **發布視圖**：
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-views"
  ```
- **發布翻譯文件**：
  ```bash
  php artisan vendor:publish --tag="jetstream-chat-lang"
  ```

### 3. 運行資料庫遷移

發佈完資料庫遷移文件後，執行：

```bash
php artisan migrate
```

這將建立下列資料表：
- `conversations`：存放對話資料（類型與團隊關聯）。
- `conversation_participants`：記錄對話參與者、未讀訊息計數及最後閱讀時間。
- `messages`：儲存每則消息的內容與發送者資料。

### 4. 在 User 模型中添加 `HasConversations` 特徵

為了啟用未讀消息計數與對話關係，請在 `app/Models/User.php` 中加入以下程式碼：

```php
use Bleuren\JetstreamChat\Traits\HasConversations;

class User extends Authenticatable
{
    use HasConversations;
    // 其他模型設定…
}
```

### 5. 設置廣播

為了實現實時消息與通知，請確保您已正確配置 Laravel 的廣播功能。若使用 Pusher 驅動，請在 `.env` 中設置：

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

同時，在前端 JavaScript 中設定 Laravel Echo，例如在 `resources/js/bootstrap.js`：

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

詳情請參考 [Laravel Broadcasting Documentation](https://laravel.com/docs/12.x/broadcasting) 與 [Laravel Echo Documentation](https://laravel.com/docs/12.x/broadcasting#client-side-installation)。

---

## 配置

發佈後，您可以在 `config/jetstream-chat.php` 中調整以下配置：
- **path**: 聊天頁面 URL 路徑（預設為 `chat`）。
- **messages_per_page**: 聊天框每頁顯示的消息筆數（預設 50）。
- **search_min_characters**: 啟動用戶搜尋的最小字元數（預設 2）。
- **user_model**: 可指定自訂的使用者模型，預設使用 Laravel 認證模型。

您也可以根據需要覆寫套件的視圖與翻譯文件。

---

## 使用說明

### 訪問聊天頁面

- 聊天頁面默認路由為 `/chat`（可在配置文件中更改）。
- 使用者登入後即可看到聊天介面，包含左側的會話列表與右側的聊天視窗。

### 啟動新聊天

- **私人聊天**：點擊「新私人」按鈕後，系統會開啟模態框讓您搜尋並選擇其他用戶進行私人對話。如果該用戶已有對話，則直接切換。
- **團隊聊天**：點擊「新團隊聊天」按鈕，選擇您所屬的團隊以創建團隊聊天室。注意：此功能需要 Jetstream 團隊支持。

### 即時功能

- **消息發送與接收**：利用 Livewire 與 Laravel Echo，聊天視窗可即時接收新消息。當使用者發送消息時，消息會被廣播至對話中的其他用戶，並自動標記為已讀。
- **通知**：頁面頂部的鈴鐺圖示會顯示未讀消息數。點擊鈴鐺可展開通知視窗，並提供「標記所有為已讀」功能。

---

## 功能特性

- **即時聊天**：基於 Laravel Echo 與 Broadcasting 實現消息的實時傳送與接收。
- **多對話支持**：支援私人對話與團隊聊天室，自動管理對話參與者與未讀消息計數。
- **自動標記已讀**：當使用者查看聊天內容時，消息將自動標記為已讀，並透過廣播更新通知。
- **用戶搜尋**：內建模態框與用戶搜尋功能，方便發起新的私人聊天。
- **多語系支持**：預設提供英文與繁體中文翻譯，可根據需求進行擴充與自訂。

---

## 自定義與擴充

Jetstream Chat 設計上注重高度擴充性，您可以：

- **覆寫視圖**  
  發佈後，視圖位於 `resources/views/vendor/jetstream-chat`，您可以根據專案風格修改介面。

- **調整配置**  
  修改 `config/jetstream-chat.php` 以調整聊天路徑、消息分頁數、搜尋字符限制等參數。

- **自訂翻譯**  
  修改 `lang/vendor/jetstream-chat` 中的語系檔案，新增或修改翻譯內容。

- **擴展事件邏輯**  
  參考 `src/Events` 目錄中的事件實作（如 `ConversationCreated`、`MessageCreated`、`ConversationRead`），根據需要擴展廣播或事件處理流程。

---

## 貢獻

我們歡迎任何形式的貢獻與反饋！  
- 如有 Bug 報告或功能建議，請在 [GitHub Issues](https://github.com/bleuren/jetstream-chat/issues) 中提交。
- 如果您有改進建議，歡迎提出 Pull Request，請遵循標準的分支管理與提交規範。

---

## 許可證

Jetstream Chat 採用 [MIT License](LICENSE.md) 授權。您可以自由使用、修改與分發此套件，詳情請參閱 LICENSE 文件。
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get(config('jetstream-chat.path', 'chat'), function () {
        return view('jetstream-chat::chat');
    })->name('jetstream-chat');
});

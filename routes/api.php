<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::get('/chat/{withUserId}', [ChatController::class, 'getMessages']);
});

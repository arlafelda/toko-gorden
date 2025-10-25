<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::middleware('auth:sanctum')->group(function () {
    // Stream Chat API endpoints
    Route::post('/chat/token', [ChatController::class, 'generateToken']);
    Route::post('/chat/channel', [ChatController::class, 'createChat']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    
    // User list for admin
    Route::get('/chat/users', [ChatController::class, 'userList']);
});

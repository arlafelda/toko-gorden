<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}.user', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat.{adminId}.admin', function ($admin, $adminId) {
    return (int) $admin->id === (int) $adminId;
});



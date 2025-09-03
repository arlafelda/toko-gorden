<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (auth('web')->check() && auth('web')->id() == $id)
        || (auth('admin')->check() && auth('admin')->id() == $id);
});


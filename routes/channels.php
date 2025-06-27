<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('online-users', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

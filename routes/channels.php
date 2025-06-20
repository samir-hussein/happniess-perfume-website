<?php

use App\Models\Client;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user = null, int $chatId) {
	return true;
});

Broadcast::channel('chats', function ($user = null) {
	return true;
});

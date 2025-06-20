<?php

namespace App\Handlers;

use App\Interfaces\IMessageRepo;
use App\Interfaces\IChatRepo;
use App\Events\MessageSent;
use App\Events\ChatEvent;
use App\Models\FCMToken;
use App\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class SendMessageHandler
{
    public function __construct(
        private IMessageRepo $messageRepo,
        private IChatRepo $chatRepo
    ) {}

    public function __invoke(string $message)
    {
        $chat = $this->chatRepo->getMyChatOrCreateNew();

        $message = $this->messageRepo->sendMessage($message, $chat->id);

        event(new MessageSent($chat, $message));

        event(new ChatEvent($chat));

        // send notification
        $payload = [
            'title' => "New Message From " . (request()->user()?->name ?? "Guest-" . request()->cookie('chat_id')),
            'message' => $message->content,
            'url' => env("PANEL_URL") . "/messages"
        ];

        $tokens = FCMToken::get()->pluck('token')->toArray();

        try {
            Firebase::sendNotification($tokens, $payload);
        } catch (\Throwable $th) {
            Log::info($th->getMessage() . " : " . $th->getTraceAsString());
        }

        return $message;
    }
}

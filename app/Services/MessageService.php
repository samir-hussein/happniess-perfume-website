<?php

namespace App\Services;

use App\Interfaces\IMessageService;
use App\Handlers\SendMessageHandler;
use App\Handlers\MarkMessagesAsRead;

class MessageService implements IMessageService
{
    public function __construct(private SendMessageHandler $sendMessageHandler, private MarkMessagesAsRead $markMessagesAsReadHandler) {}

    public function sendMessage(string $message)
    {
        return $this->sendMessageHandler->__invoke($message);
    }

    public function markAsRead()
    {
        return $this->markMessagesAsReadHandler->__invoke();
    }
}

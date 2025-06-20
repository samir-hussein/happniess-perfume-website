<?php

namespace App\Interfaces;

interface IMessageService
{
    public function sendMessage(string $message);
    public function markAsRead();
}

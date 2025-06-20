<?php

namespace App\Interfaces;

interface IMessageRepo extends IRepository
{
	public function sendMessage(string $message, int $chatId);
	public function markAsRead(int $chatId);
}

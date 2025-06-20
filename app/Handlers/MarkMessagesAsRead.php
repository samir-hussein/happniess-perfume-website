<?php

namespace App\Handlers;

use App\Interfaces\IMessageRepo;
use App\Interfaces\IChatRepo;

class MarkMessagesAsRead
{
	public function __construct(
		private IMessageRepo $messageRepo,
		private IChatRepo $chatRepo
	) {}

	public function __invoke()
	{
		$chat = $this->chatRepo->getMyChat();
		if (!$chat) {
			return;
		}
		$this->messageRepo->markAsRead($chat->id);
	}
}

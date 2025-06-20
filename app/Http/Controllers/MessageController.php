<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Interfaces\IMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MessageController extends Controller
{
	public function __construct(private IMessageService $messageService) {}

	public function sendMessage(SendMessageRequest $request)
	{
		$message = $this->messageService->sendMessage($request->message);

		return response()->json([
			"chat_id" => $message->chat_id,
			'message' => [
				"content" => $message->content,
				"type" => $message->type,
				"sender" => $message->sender,
				"sender_name" => $message->sender_name,
				"read" => $message->read,
				"time" => $message->created_at->format('Y-m-d h:i a'),
			]
		]);
	}

	public function markAsRead(Request $request)
	{
		$this->messageService->markAsRead();

		if (!$request->hasCookie('chat_id')) {
			$chatId = (string) Str::uuid();

			// Attach the cookie to the response
			return response()->json([
				"status" => "success",
			])->cookie('chat_id', $chatId, 60 * 24 * 365);
		}

		return response()->json([
			"status" => "success",
		]);
	}
}

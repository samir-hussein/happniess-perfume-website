<?php

namespace App\Repositories;

use App\Interfaces\IMessageRepo;
use App\Models\Message;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MessageRepo extends BaseRepository implements IMessageRepo
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    public function sendMessage(string $message, int $chatId)
    {
        return $this->model->create([
            'chat_id' => $chatId,
            'content' => $message,
            'type' => 'text',
            'sender' => 'client',
            'sender_name' => Auth::user()?->name ?? 'Guest-' . request()->cookie('chat_id'),
            'read' => false,
        ]);
    }

    public function markAsRead(int $chatId)
    {
        return $this->model->where('chat_id', $chatId)->where('sender', 'admin')->update(['read' => true]);
    }
}

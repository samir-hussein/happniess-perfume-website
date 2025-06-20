<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $chat;
    protected $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Chat $chat, Message $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('chat.' . $this->chat->id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith()
    {
        return [
            'chat_id' => $this->chat->id,
            'message' => [
                "content" => $this->message->content,
                "type" => $this->message->type,
                "sender" => $this->message->sender,
                "sender_name" => $this->message->sender_name,
                "read" => $this->message->read,
                "time" => $this->message->created_at->format('Y-m-d h:i a'),
            ],
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        "chat_id",
        "content",
        "type",
        "sender",
        "sender_name",
        "admin_id",
        "read",
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class);
    }
}

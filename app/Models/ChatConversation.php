<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $table = 'chat_conversations';

    protected $fillable = [
        'user_id',
        'session_id',
        'message',
        'response',
        'intent',
        'confidence',
    ];
}

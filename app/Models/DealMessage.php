<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'conversation_id',
        'message',
        'file_path',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function conversation()
    {
        return $this->belongsTo(DealConversation::class, 'conversation_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealConversation extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'deal_id',
        'status',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function messages()
    {
        return $this->hasMany(DealMessage::class, 'conversation_id');
    }
}
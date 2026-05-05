<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealInterest extends Model
{
    protected $fillable = [
        'deal_id',
        'buyer_id',
        'type',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
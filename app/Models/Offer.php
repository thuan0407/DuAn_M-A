<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'deal_id',
        'buyer_id',
        'seller_id',
        'offer_type',
        'amount',
        'equity_percent',
        'currency',
        'note',
        'status',
        'buyer_confirmed_at',
        'seller_confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'equity_percent' => 'decimal:2',
        'buyer_confirmed_at' => 'datetime',
        'seller_confirmed_at' => 'datetime',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
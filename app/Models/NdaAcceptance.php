<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NdaAcceptance extends Model
{
    protected $fillable = [
        'deal_id',
        'buyer_id',
        'nda_version',
        'accepted_at',
        'accepted_ip',
        'status',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function accessRequests()
    {
        return $this->hasMany(DealAccessRequest::class);
    }
}
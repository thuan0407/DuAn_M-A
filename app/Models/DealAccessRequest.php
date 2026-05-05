<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealAccessRequest extends Model
{
    protected $fillable = [
        'nda_acceptance_id',
        'buyer_id',
        'deal_id',
        'rejection_reason',
        'status',
    ];

    public function ndaAcceptance()
    {
        return $this->belongsTo(NdaAcceptance::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
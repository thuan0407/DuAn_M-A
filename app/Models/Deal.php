<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'seller_id',
        'company_id',
        'title',
        'deal_type',
        'industry',
        'location',
        'short_description',
        'full_description',
        'target_amount',
        'valuation',
        'equity_offered_percent',
        'min_ticket',
        'allow_multiple_investors',
        'currency',
        'status',
        'committed_amount',
        'confirmed_amount',
        'rejection_reason',
        'published_at',
        'closed_at',
    ];

    protected $casts = [
        'allow_multiple_investors' => 'boolean',
        'target_amount' => 'decimal:2',
        'valuation' => 'decimal:2',
        'equity_offered_percent' => 'decimal:2',
        'min_ticket' => 'decimal:2',
        'committed_amount' => 'decimal:2',
        'confirmed_amount' => 'decimal:2',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function interests()
    {
        return $this->hasMany(DealInterest::class);
    }

    public function ndaAcceptances()
    {
        return $this->hasMany(NdaAcceptance::class);
    }

    public function accessRequests()
    {
        return $this->hasMany(DealAccessRequest::class);
    }

    public function dataRoomFiles()
    {
        return $this->hasMany(DataRoomFile::class);
    }

    public function conversations()
    {
        return $this->hasMany(DealConversation::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
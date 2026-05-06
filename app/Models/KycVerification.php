<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'document_front',
        'document_back',
        'selfie_image',
        'company',
        'position',
        'experience',
        'goal',
        'phone',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kyc()
{
    return $this->hasOne(\App\Models\KycVerification::class, 'user_id');
}
}
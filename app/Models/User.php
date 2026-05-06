<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kycVerification()
    {
        return $this->hasOne(KycVerification::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'seller_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'seller_id');
    }

    public function dealInterests()
    {
        return $this->hasMany(DealInterest::class, 'buyer_id');
    }

    public function ndaAcceptances()
    {
        return $this->hasMany(NdaAcceptance::class, 'buyer_id');
    }

    public function dealAccessRequests()
    {
        return $this->hasMany(DealAccessRequest::class, 'buyer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(DealMessage::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function kyc()
    {
        return $this->hasOne(KycVerification::class);
    }
}

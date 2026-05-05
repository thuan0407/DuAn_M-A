<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'seller_id',
        'legal_name',
        'tax_code',
        'business_registration_number',
        'address',
        'legal_representative_name',
        'industry',
        'description',
        'verification_status',
        'rejection_reason',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function documents()
    {
        return $this->hasMany(CompanyDocument::class);
    }

    public function financials()
    {
        return $this->hasMany(CompanyFinancial::class, 'company_id')
            ->orderByDesc('financial_year');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
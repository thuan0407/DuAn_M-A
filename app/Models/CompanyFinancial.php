<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFinancial extends Model
{
    protected $fillable = [
        'company_id',
        'financial_year',
        'revenue',
        'ebitda',
        'net_profit',
    ];

    protected $casts = [
        'revenue' => 'decimal:2',
        'ebitda' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
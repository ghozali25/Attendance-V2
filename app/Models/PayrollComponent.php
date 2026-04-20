<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // allowance, deduction
        'calculation_type', // fixed, percentage_basic, daily_presence
        'amount',
        'percentage',
        'is_taxable',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
    ];
}

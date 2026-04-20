<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'status',
        'payment_month',
        'payment_year',
        'approved_by',
        'approved_at',
        'head_approved_by',
        'head_approved_at',
        'finance_approved_by',
        'finance_approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'head_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function headApprover()
    {
        return $this->belongsTo(User::class, 'head_approved_by');
    }

    public function financeApprover()
    {
        return $this->belongsTo(User::class, 'finance_approved_by');
    }
}

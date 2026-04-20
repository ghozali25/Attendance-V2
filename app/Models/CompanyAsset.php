<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAsset extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'type',
        'purchase_date',
        'purchase_cost',
        'expiration_date',
        'user_id',
        'date_assigned',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_assigned' => 'date',
        'return_date' => 'date',
        'purchase_date' => 'date',
        'expiration_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    /**
     * Get the user that was assigned this asset.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(CompanyAssetHistory::class)->orderBy('date', 'desc');
    }

    public function isExpired()
    {
        if (!$this->expiration_date) return false;
        return now()->startOfDay()->greaterThan($this->expiration_date);
    }

    public function isExpiringSoon()
    {
        if (!$this->expiration_date || $this->isExpired()) return false;
        return now()->startOfDay()->diffInDays($this->expiration_date, false) <= 30;
    }
}

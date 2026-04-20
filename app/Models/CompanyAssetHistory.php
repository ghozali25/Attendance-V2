<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAssetHistory extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'company_asset_id',
        'user_id',
        'action',
        'notes',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(CompanyAsset::class, 'company_asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

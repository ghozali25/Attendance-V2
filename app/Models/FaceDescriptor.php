<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceDescriptor extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'descriptor',
    ];

    protected $casts = [
        'descriptor' => 'array',
    ];

    /**
     * Get the user that owns the face descriptor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the descriptor as a flat array of floats.
     */
    public function getDescriptorArrayAttribute(): array
    {
        return $this->descriptor ?? [];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $fillable = [
        'name',
        'level', // Deprecated, use job_level_id
        'job_level_id',
        'division_id',
    ];

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}

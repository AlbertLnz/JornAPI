<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HourWorked extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'hour_session_id',
        'total_normal_hours',
        'total_overtime_hours',
        'total_night_hours',
        'total_holiday_hours',
    ];

    public function hourSession(): BelongsTo{
        return $this->belongsTo(HourSession::class);
    }

}

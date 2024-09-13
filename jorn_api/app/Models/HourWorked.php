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
        'employee_id',  
        'date',
        'start_time',
        'end_time',
        'planned_hours',
        'is_holiday',
        'is_overtime'
    ];

    public function employee(): BelongsTo{
        return $this->belongsTo(Employee::class);
    }
}

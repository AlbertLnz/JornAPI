<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'normal_hourly_rate',
        'overtime_hourly_rate',
        'night_hourly_rate',
        'holiday_hourly_rate',
        'user_id',
        'irpf'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hourWorkeds()
    {
        return $this->hasMany(HourWorked::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHourPolicy extends Model
{
    protected $fillable = ['name', 'hours_per_day', 'days_per_week', 'week_start', 'grace_minutes', 'overtime_after_minutes', 'is_default'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'hours_per_day' => 'decimal:2'];
    }
}

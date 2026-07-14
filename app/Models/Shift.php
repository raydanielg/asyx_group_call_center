<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['name', 'code', 'start_time', 'end_time', 'crosses_midnight', 'break_minutes', 'grace_minutes', 'color', 'is_night_shift', 'night_allowance', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'crosses_midnight' => 'boolean', 'is_night_shift' => 'boolean', 'night_allowance' => 'decimal:2'];
    }

    public function assignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }
}

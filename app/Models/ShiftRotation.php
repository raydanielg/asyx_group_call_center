<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftRotation extends Model
{
    protected $fillable = ['name', 'pattern', 'cycle_days', 'applies_to', 'team_id', 'department_id', 'starts_on', 'is_active'];

    protected function casts(): array
    {
        return ['pattern' => 'array', 'starts_on' => 'date', 'is_active' => 'boolean'];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

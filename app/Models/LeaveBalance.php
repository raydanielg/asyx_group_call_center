<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = ['employee_id', 'leave_type_id', 'year', 'entitled', 'carried_over', 'used'];

    protected function casts(): array
    {
        return ['entitled' => 'decimal:2', 'carried_over' => 'decimal:2', 'used' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getRemainingAttribute()
    {
        return $this->entitled + $this->carried_over - $this->used;
    }
}

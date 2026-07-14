<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'shift_assignment_id', 'check_in', 'check_out',
        'status', 'late_minutes', 'early_leave_minutes', 'worked_minutes',
        'overtime_minutes', 'overtime_approved', 'source', 'remarks', 'recorded_by'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in' => 'datetime',
            'check_out' => 'datetime',
            'overtime_approved' => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftAssignment()
    {
        return $this->belongsTo(ShiftAssignment::class);
    }

    public function corrections()
    {
        return $this->hasMany(AttendanceCorrection::class);
    }
}

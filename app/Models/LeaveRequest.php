<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = ['employee_id', 'leave_type_id', 'start_date', 'end_date', 'days', 'half_day', 'reason', 'attachment_path', 'status', 'decided_by', 'decided_at', 'decision_note', 'recorded_by'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'decided_at' => 'datetime'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function decidedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'decided_by');
    }
}

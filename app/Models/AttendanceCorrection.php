<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    public $timestamps = false;

    protected $fillable = ['attendance_record_id', 'field', 'old_value', 'new_value', 'reason', 'corrected_by', 'corrected_at'];

    protected function casts(): array
    {
        return ['corrected_at' => 'datetime'];
    }

    public function record()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }
}

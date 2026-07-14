<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachingNote extends Model
{
    protected $fillable = ['employee_id', 'quality_evaluation_id', 'note', 'action_items', 'follow_up_date', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['follow_up_date' => 'date'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function qualityEvaluation()
    {
        return $this->belongsTo(QualityEvaluation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}

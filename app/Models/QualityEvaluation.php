<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityEvaluation extends Model
{
    protected $fillable = ['employee_id', 'form_id', 'call_reference', 'evaluated_at', 'evaluator_user_id', 'scores', 'total_score', 'percentage', 'outcome', 'summary'];

    protected function casts(): array
    {
        return ['evaluated_at' => 'datetime', 'scores' => 'array', 'total_score' => 'decimal:2', 'percentage' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function form()
    {
        return $this->belongsTo(QualityEvaluationForm::class, 'form_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function coachingNotes()
    {
        return $this->hasMany(CoachingNote::class);
    }
}

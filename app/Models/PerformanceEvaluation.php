<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    protected $fillable = ['employee_id', 'period_year', 'period_month', 'kpi_scores', 'weighted_score', 'rank_in_team', 'rank_in_company', 'grade', 'evaluated_by', 'comments', 'status'];

    protected function casts(): array
    {
        return ['kpi_scores' => 'array', 'weighted_score' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}

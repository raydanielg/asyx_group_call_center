<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityEvaluationForm extends Model
{
    protected $fillable = ['name', 'description', 'max_score', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function criteria()
    {
        return $this->hasMany(QualityFormCriterion::class, 'form_id');
    }

    public function evaluations()
    {
        return $this->hasMany(QualityEvaluation::class, 'form_id');
    }
}

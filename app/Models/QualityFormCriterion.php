<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityFormCriterion extends Model
{
    public $timestamps = false;

    protected $fillable = ['form_id', 'label', 'weight', 'max_points', 'sort_order'];

    protected function casts(): array
    {
        return ['weight' => 'decimal:2'];
    }

    public function form()
    {
        return $this->belongsTo(QualityEvaluationForm::class, 'form_id');
    }
}

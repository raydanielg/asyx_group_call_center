<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingTask extends Model
{
    public $timestamps = false;

    protected $fillable = ['onboarding_id', 'task_title', 'is_done', 'done_at', 'done_by'];

    protected function casts(): array
    {
        return ['is_done' => 'boolean', 'done_at' => 'datetime'];
    }

    public function onboarding()
    {
        return $this->belongsTo(EmployeeOnboarding::class, 'onboarding_id');
    }
}

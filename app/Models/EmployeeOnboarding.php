<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    protected $fillable = ['employee_id', 'checklist_id', 'started_at', 'completed_at', 'status'];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function checklist()
    {
        return $this->belongsTo(OnboardingChecklist::class, 'checklist_id');
    }

    public function tasks()
    {
        return $this->hasMany(EmployeeOnboardingTask::class, 'onboarding_id');
    }
}

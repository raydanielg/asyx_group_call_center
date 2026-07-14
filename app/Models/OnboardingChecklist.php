<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingChecklist extends Model
{
    protected $fillable = ['name', 'is_default'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    public function tasks()
    {
        return $this->hasMany(OnboardingTask::class, 'checklist_id');
    }
}

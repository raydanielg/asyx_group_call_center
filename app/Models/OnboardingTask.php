<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    public $timestamps = false;

    protected $fillable = ['checklist_id', 'title', 'description', 'sort_order'];

    public function checklist()
    {
        return $this->belongsTo(OnboardingChecklist::class, 'checklist_id');
    }
}

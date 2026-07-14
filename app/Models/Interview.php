<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = ['applicant_id', 'round', 'type', 'scheduled_at', 'duration_minutes', 'interviewer_user_id', 'location', 'status', 'score', 'feedback'];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_user_id');
    }
}

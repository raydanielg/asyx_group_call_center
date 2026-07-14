<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = ['job_position_id', 'first_name', 'last_name', 'email', 'phone', 'cv_path', 'cover_letter', 'source', 'stage', 'rejected_reason', 'rating', 'notes', 'created_by'];

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

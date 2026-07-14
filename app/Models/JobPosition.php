<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = ['title', 'department_id', 'position_id', 'description', 'requirements', 'openings', 'employment_type', 'salary_range_min', 'salary_range_max', 'status', 'opened_at', 'closes_at', 'created_by'];

    protected function casts(): array
    {
        return ['opened_at' => 'datetime', 'closes_at' => 'date', 'salary_range_min' => 'decimal:2', 'salary_range_max' => 'decimal:2'];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}

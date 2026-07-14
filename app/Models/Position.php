<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['department_id', 'title', 'code', 'level', 'min_salary', 'max_salary', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'min_salary' => 'decimal:2', 'max_salary' => 'decimal:2'];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

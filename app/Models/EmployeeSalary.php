<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = ['employee_id', 'effective_from', 'base_salary', 'pay_frequency', 'created_by'];

    protected function casts(): array
    {
        return ['effective_from' => 'date', 'base_salary' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

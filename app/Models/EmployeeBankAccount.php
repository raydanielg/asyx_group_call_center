<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankAccount extends Model
{
    protected $fillable = ['employee_id', 'bank_name', 'branch', 'account_name', 'account_number', 'is_primary', 'mobile_money_provider', 'mobile_money_number'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

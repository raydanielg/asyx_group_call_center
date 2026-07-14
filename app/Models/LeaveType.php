<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = ['name', 'code', 'days_per_year', 'is_paid', 'carry_forward', 'max_carry_forward', 'requires_attachment', 'gender_restriction', 'is_active'];

    protected function casts(): array
    {
        return ['is_paid' => 'boolean', 'carry_forward' => 'boolean', 'requires_attachment' => 'boolean', 'is_active' => 'boolean', 'days_per_year' => 'decimal:2', 'max_carry_forward' => 'decimal:2'];
    }

    public function requests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function balances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEmergencyContact extends Model
{
    protected $fillable = ['employee_id', 'name', 'relationship', 'phone', 'alt_phone', 'address'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

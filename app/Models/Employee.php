<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_code', 'first_name', 'middle_name', 'last_name', 'gender',
        'date_of_birth', 'marital_status', 'national_id', 'nssf_number', 'tin_number', 'photo_path',
        'email', 'phone', 'alt_phone', 'address', 'city', 'country',
        'branch_id', 'department_id', 'position_id', 'team_id',
        'employment_type', 'hire_date', 'probation_end_date', 'contract_end_date',
        'employment_status', 'termination_date', 'termination_reason',
        'reports_to', 'notes', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'date_of_birth' => 'date',
            'probation_end_date' => 'date',
            'contract_end_date' => 'date',
            'termination_date' => 'date',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function reportsTo()
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(EmployeeBankAccount::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class)->orderBy('effective_from', 'desc');
    }

    public function currentSalary()
    {
        return $this->salaries()->where('effective_from', '<=', now())->first();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function dailyStats()
    {
        return $this->hasMany(AgentDailyStat::class);
    }

    public function evaluations()
    {
        return $this->hasMany(PerformanceEvaluation::class);
    }

    public function fullName()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }
}

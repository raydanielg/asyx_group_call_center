<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_run_id', 'employee_id', 'basic_salary', 'total_allowances', 'total_deductions',
        'overtime_hours', 'overtime_amount', 'bonus', 'commission', 'gross_pay', 'net_pay',
        'working_days', 'present_days', 'absent_days', 'leave_days', 'pdf_path', 'status'
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2', 'total_allowances' => 'decimal:2', 'total_deductions' => 'decimal:2',
            'overtime_hours' => 'decimal:2', 'overtime_amount' => 'decimal:2', 'bonus' => 'decimal:2',
            'commission' => 'decimal:2', 'gross_pay' => 'decimal:2', 'net_pay' => 'decimal:2',
        ];
    }

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function lines()
    {
        return $this->hasMany(PayslipLine::class);
    }
}

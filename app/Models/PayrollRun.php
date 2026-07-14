<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    protected $fillable = ['period_year', 'period_month', 'status', 'total_gross', 'total_deductions', 'total_net', 'employee_count', 'processed_by', 'approved_by', 'paid_at'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'total_gross' => 'decimal:2', 'total_deductions' => 'decimal:2', 'total_net' => 'decimal:2'];
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getPeriodNameAttribute()
    {
        return date('F Y', mktime(0, 0, 0, $this->period_month, 1, $this->period_year));
    }
}

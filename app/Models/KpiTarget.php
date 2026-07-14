<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiTarget extends Model
{
    protected $fillable = ['kpi_id', 'scope', 'department_id', 'team_id', 'employee_id', 'period_year', 'period_month', 'target_value'];

    protected function casts(): array
    {
        return ['target_value' => 'decimal:4'];
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

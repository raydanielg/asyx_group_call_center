<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = ['employee_id', 'period_year', 'period_month', 'amount', 'reason', 'created_by'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentDailyStat extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'total_calls', 'answered_calls', 'missed_calls',
        'outbound_calls', 'talk_time_seconds', 'hold_time_seconds', 'wrap_time_seconds',
        'aht_seconds', 'conversions', 'csat_score', 'source'
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'csat_score' => 'decimal:2'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AgentDailyStat;
use App\Models\Employee;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function overview(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->toDateString());
        $to = $request->get('to', now()->toDateString());
        $departmentId = $request->get('department_id');
        $employeeId = $request->get('employee_id');

        $query = AgentDailyStat::with('employee')->whereBetween('date', [$from, $to]);
        if ($employeeId) $query->where('employee_id', $employeeId);
        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $stats = $query->get();

        $summary = [
            'total_calls' => $stats->sum('total_calls'),
            'answered_calls' => $stats->sum('answered_calls'),
            'missed_calls' => $stats->sum('missed_calls'),
            'outbound_calls' => $stats->sum('outbound_calls'),
            'conversions' => $stats->sum('conversions'),
            'avg_aht' => round($stats->avg('aht_seconds') ?? 0, 1),
            'avg_csat' => round($stats->avg('csat_score') ?? 0, 2),
            'answer_rate' => $stats->sum('total_calls') > 0 ? round(($stats->sum('answered_calls') / $stats->sum('total_calls')) * 100, 1) : 0,
            'conversion_rate' => $stats->sum('answered_calls') > 0 ? round(($stats->sum('conversions') / $stats->sum('answered_calls')) * 100, 2) : 0,
        ];

        // Daily trend
        $dailyData = $stats->groupBy(function($s) { return $s->date->format('M d'); });
        $trendLabels = $dailyData->keys()->toArray();
        $callsData = $dailyData->map(fn($g) => $g->sum('total_calls'))->toArray();
        $ahtData = $dailyData->map(fn($g) => round($g->avg('aht_seconds') ?? 0, 1))->toArray();
        $csatData = $dailyData->map(fn($g) => round($g->avg('csat_score') ?? 0, 2))->toArray();

        // Team comparison
        $teamComparison = $stats->groupBy(function($s) {
            return $s->employee->department->name ?? 'Unassigned';
        })->map(function($g) {
            return [
                'calls' => $g->sum('total_calls'),
                'aht' => round($g->avg('aht_seconds') ?? 0, 1),
                'csat' => round($g->avg('csat_score') ?? 0, 2),
                'conversions' => $g->sum('conversions'),
            ];
        })->toArray();

        // Day of week heatmap
        $heatmap = [];
        foreach ($stats as $s) {
            $day = $s->date->format('D');
            if (!isset($heatmap[$day])) $heatmap[$day] = 0;
            $heatmap[$day] += $s->total_calls;
        }

        $departments = \App\Models\Department::where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'active')->get();

        return view('analytics.overview', compact(
            'summary', 'trendLabels', 'callsData', 'ahtData', 'csatData',
            'teamComparison', 'heatmap', 'departments', 'employees',
            'from', 'to'
        ));
    }

    public function dataEntry(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'date' => 'required|date',
                'total_calls' => 'required|integer|min:0',
                'answered_calls' => 'nullable|integer|min:0',
                'missed_calls' => 'nullable|integer|min:0',
                'outbound_calls' => 'nullable|integer|min:0',
                'talk_time_seconds' => 'nullable|integer|min:0',
                'hold_time_seconds' => 'nullable|integer|min:0',
                'wrap_time_seconds' => 'nullable|integer|min:0',
                'conversions' => 'nullable|integer|min:0',
                'csat_score' => 'nullable|numeric|min:0|max:5',
            ]);

            $talkTime = $validated['talk_time_seconds'] ?? 0;
            $holdTime = $validated['hold_time_seconds'] ?? 0;
            $wrapTime = $validated['wrap_time_seconds'] ?? 0;
            $answered = $validated['answered_calls'] ?? 0;
            $validated['aht_seconds'] = $answered > 0 ? (int)(($talkTime + $holdTime + $wrapTime) / $answered) : 0;
            $validated['source'] = 'manual';

            AgentDailyStat::updateOrCreate(
                ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
                $validated
            );

            return back()->with('success', 'Stats saved.');
        }

        $employees = Employee::where('employment_status', 'active')->orderBy('first_name')->get();
        $recentStats = AgentDailyStat::with('employee')->orderBy('date', 'desc')->take(20)->get();

        return view('analytics.data-entry', compact('employees', 'recentStats'));
    }
}

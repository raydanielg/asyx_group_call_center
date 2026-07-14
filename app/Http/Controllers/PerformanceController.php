<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\KpiTarget;
use App\Models\PerformanceEvaluation;
use App\Models\Employee;
use App\Models\AgentDailyStat;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kpisIndex()
    {
        $kpis = Kpi::orderBy('name')->paginate(15);
        return view('performance.kpis', compact('kpis'));
    }

    public function kpisStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:kpis,code',
            'unit' => 'required|in:count,percent,seconds,score,currency',
            'direction' => 'required|in:higher_better,lower_better',
            'weight' => 'required|numeric|min:0',
            'applies_to' => 'required|in:agent,team',
        ]);
        Kpi::create($validated);
        return back()->with('success', 'KPI created.');
    }

    public function kpisUpdate(Request $request, Kpi $kpi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:kpis,code,' . $kpi->id,
            'unit' => 'required|in:count,percent,seconds,score,currency',
            'direction' => 'required|in:higher_better,lower_better',
            'weight' => 'required|numeric|min:0',
            'applies_to' => 'required|in:agent,team',
            'is_active' => 'boolean',
        ]);
        $kpi->update($validated);
        return back()->with('success', 'KPI updated.');
    }

    public function kpisDestroy(Kpi $kpi)
    {
        $kpi->delete();
        return back()->with('success', 'KPI deleted.');
    }

    public function targetsIndex()
    {
        $targets = KpiTarget::with(['kpi', 'department', 'team', 'employee'])->orderBy('period_year', 'desc')->paginate(20);
        $kpis = Kpi::where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();
        $teams = \App\Models\Team::where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'active')->get();
        return view('performance.targets', compact('targets', 'kpis', 'departments', 'teams', 'employees'));
    }

    public function targetsStore(Request $request)
    {
        $validated = $request->validate([
            'kpi_id' => 'required|exists:kpis,id',
            'scope' => 'required|in:company,department,team,employee',
            'department_id' => 'nullable|exists:departments,id',
            'team_id' => 'nullable|exists:teams,id',
            'employee_id' => 'nullable|exists:employees,id',
            'period_year' => 'required|integer',
            'period_month' => 'nullable|integer|min:1|max:12',
            'target_value' => 'required|numeric',
        ]);
        KpiTarget::create($validated);
        return back()->with('success', 'Target set.');
    }

    public function targetsDestroy(KpiTarget $target)
    {
        $target->delete();
        return back()->with('success', 'Target deleted.');
    }

    public function evaluationsIndex(Request $request)
    {
        $query = PerformanceEvaluation::with(['employee.department', 'evaluatedBy']);

        if ($request->filled('year')) {
            $query->where('period_year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }

        $evaluations = $query->orderBy('period_year', 'desc')->orderBy('period_month', 'desc')->paginate(15);
        return view('performance.evaluations', compact('evaluations'));
    }

    public function evaluationsGenerate(Request $request)
    {
        $validated = $request->validate([
            'period_year' => 'required|integer',
            'period_month' => 'required|integer|min:1|max:12',
        ]);

        $kpis = Kpi::where('is_active', true)->get();
        $employees = Employee::where('employment_status', 'active')->get();
        $count = 0;

        foreach ($employees as $employee) {
            $kpiScores = [];
            $weightedTotal = 0;
            $weightSum = 0;

            foreach ($kpis as $kpi) {
                $target = KpiTarget::where('kpi_id', $kpi->id)
                    ->where('period_year', $validated['period_year'])
                    ->where(function($q) use ($employee, $validated) {
                        $q->where('scope', 'company')
                          ->orWhere(function($sq) use ($employee, $validated) {
                              $sq->where('scope', 'employee')->where('employee_id', $employee->id);
                          })
                          ->orWhere(function($sq) use ($employee, $validated) {
                              $sq->where('scope', 'department')->where('department_id', $employee->department_id);
                          })
                          ->orWhere(function($sq) use ($employee, $validated) {
                              $sq->where('scope', 'team')->where('team_id', $employee->team_id);
                          });
                    })
                    ->first();

                $targetValue = $target ? $target->target_value : 0;

                // Get actual from agent_daily_stats
                $actual = AgentDailyStat::where('employee_id', $employee->id)
                    ->whereMonth('date', $validated['period_month'])
                    ->whereYear('date', $validated['period_year'])
                    ->sum($this->getKpiField($kpi->code));

                $score = $targetValue > 0 ? min(100, ($actual / $targetValue) * 100) : 0;
                if ($kpi->direction === 'lower_better' && $targetValue > 0) {
                    $score = min(100, ($targetValue / max($actual, 1)) * 100);
                }

                $kpiScores[] = ['kpi_id' => $kpi->id, 'kpi_name' => $kpi->name, 'actual' => $actual, 'target' => $targetValue, 'score' => round($score, 2)];
                $weightedTotal += $score * $kpi->weight;
                $weightSum += $kpi->weight;
            }

            $weightedScore = $weightSum > 0 ? round($weightedTotal / $weightSum, 2) : 0;
            $grade = $this->scoreToGrade($weightedScore);

            PerformanceEvaluation::updateOrCreate(
                ['employee_id' => $employee->id, 'period_year' => $validated['period_year'], 'period_month' => $validated['period_month']],
                [
                    'kpi_scores' => $kpiScores,
                    'weighted_score' => $weightedScore,
                    'grade' => $grade,
                    'evaluated_by' => auth()->id(),
                    'status' => 'draft',
                ]
            );
            $count++;
        }

        return back()->with('success', "Generated {$count} evaluations.");
    }

    public function evaluationsShow(PerformanceEvaluation $evaluation)
    {
        $evaluation->load(['employee.department', 'evaluatedBy']);
        return view('performance.evaluation-show', compact('evaluation'));
    }

    public function evaluationsFinalize(PerformanceEvaluation $evaluation)
    {
        $evaluation->update(['status' => 'finalized']);
        return back()->with('success', 'Evaluation finalized.');
    }

    public function leaderboard(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $evaluations = PerformanceEvaluation::with(['employee.department', 'employee.position'])
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->orderBy('weighted_score', 'desc')
            ->get();

        return view('performance.leaderboard', compact('evaluations', 'year', 'month'));
    }

    private function getKpiField($code)
    {
        $map = [
            'total_calls' => 'total_calls',
            'answered_calls' => 'answered_calls',
            'aht' => 'aht_seconds',
            'csat' => 'csat_score',
            'conversions' => 'conversions',
        ];
        return $map[$code] ?? 'total_calls';
    }

    private function scoreToGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }
}

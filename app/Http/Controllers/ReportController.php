<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceRecord;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\LeaveRequest;
use App\Models\Applicant;
use App\Models\JobPosition;
use App\Models\PerformanceEvaluation;
use App\Models\AgentDailyStat;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('reports.index');
    }

    public function employees(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->toDateString());
        $to = $request->get('to', now()->toDateString());

        $total = Employee::count();
        $active = Employee::where('employment_status', 'active')->count();
        $onProbation = Employee::where('employment_status', 'probation')->count();
        $terminated = Employee::whereIn('employment_status', ['terminated', 'resigned'])->count();

        $byDept = Employee::with('department')->where('employment_status', 'active')->get()
            ->groupBy(fn($e) => $e->department->name ?? 'Unassigned')
            ->map(fn($g) => $g->count())->toArray();

        $byType = Employee::where('employment_status', 'active')->get()
            ->groupBy('employment_type')->map(fn($g) => $g->count())->toArray();

        $joiners = Employee::whereBetween('hire_date', [$from, $to])->orderBy('hire_date', 'desc')->get();
        $leavers = Employee::whereIn('employment_status', ['terminated', 'resigned'])
            ->whereBetween('termination_date', [$from, $to])->get();

        return view('reports.employees', compact('total', 'active', 'onProbation', 'terminated', 'byDept', 'byType', 'joiners', 'leavers', 'from', 'to'));
    }

    public function attendance(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->endOfMonth()->toDateString());

        $records = AttendanceRecord::with('employee')->whereBetween('date', [$from, $to])->get();

        $summary = [
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
            'total_ot' => $records->sum('overtime_minutes'),
        ];

        $byEmployee = $records->groupBy('employee_id')->map(function($g) {
            return [
                'employee' => $g->first()->employee,
                'present' => $g->where('status', 'present')->count(),
                'late' => $g->where('status', 'late')->count(),
                'absent' => $g->where('status', 'absent')->count(),
                'ot_minutes' => $g->sum('overtime_minutes'),
            ];
        })->sortByDesc('late')->values();

        return view('reports.attendance', compact('summary', 'byEmployee', 'from', 'to'));
    }

    public function payroll(Request $request)
    {
        $year = $request->get('year', now()->year);

        $runs = PayrollRun::where('period_year', $year)->orderBy('period_month')->get();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $run = $runs->where('period_month', $i)->first();
            $monthlyData[] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'gross' => $run ? (float)$run->total_gross : 0,
                'deductions' => $run ? (float)$run->total_deductions : 0,
                'net' => $run ? (float)$run->total_net : 0,
                'employees' => $run ? $run->employee_count : 0,
            ];
        }

        $totalNet = $runs->sum('total_net');
        $totalGross = $runs->sum('total_gross');
        $totalDeductions = $runs->sum('total_deductions');

        return view('reports.payroll', compact('monthlyData', 'totalNet', 'totalGross', 'totalDeductions', 'year'));
    }

    public function recruitment()
    {
        $jobs = JobPosition::withCount('applicants')->get();
        $totalApplicants = Applicant::count();
        $hired = Applicant::where('stage', 'hired')->count();
        $rejected = Applicant::where('stage', 'rejected')->count();
        $inProgress = Applicant::whereNotIn('stage', ['hired', 'rejected'])->count();

        $bySource = Applicant::get()->groupBy('source')->map(fn($g) => $g->count())->toArray();
        $byStage = Applicant::get()->groupBy('stage')->map(fn($g) => $g->count())->toArray();

        $conversionRate = $totalApplicants > 0 ? round(($hired / $totalApplicants) * 100, 1) : 0;

        return view('reports.recruitment', compact('jobs', 'totalApplicants', 'hired', 'rejected', 'inProgress', 'bySource', 'byStage', 'conversionRate'));
    }

    public function performance(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $evals = PerformanceEvaluation::with('employee')->where('period_year', $year)->where('period_month', $month)->get();

        $gradeDist = $evals->groupBy('grade')->map(fn($g) => $g->count())->toArray();
        $avgScore = $evals->avg('weighted_score');
        $topPerformers = $evals->sortByDesc('weighted_score')->take(10);

        return view('reports.performance', compact('gradeDist', 'avgScore', 'topPerformers', 'year', 'month'));
    }

    public function callCenter(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->toDateString());
        $to = $request->get('to', now()->toDateString());

        $stats = AgentDailyStat::with('employee')->whereBetween('date', [$from, $to])->get();

        $summary = [
            'total_calls' => $stats->sum('total_calls'),
            'answered' => $stats->sum('answered_calls'),
            'missed' => $stats->sum('missed_calls'),
            'conversions' => $stats->sum('conversions'),
            'avg_aht' => round($stats->avg('aht_seconds') ?? 0, 1),
            'avg_csat' => round($stats->avg('csat_score') ?? 0, 2),
        ];

        $daily = $stats->groupBy(fn($s) => $s->date->format('M d'));
        $trendLabels = $daily->keys()->toArray();
        $callsTrend = $daily->map(fn($g) => $g->sum('total_calls'))->toArray();
        $ahtTrend = $daily->map(fn($g) => round($g->avg('aht_seconds') ?? 0, 1))->toArray();
        $csatTrend = $daily->map(fn($g) => round($g->avg('csat_score') ?? 0, 2))->toArray();

        $byEmployee = $stats->groupBy('employee_id')->map(function($g) {
            return [
                'employee' => $g->first()->employee,
                'calls' => $g->sum('total_calls'),
                'aht' => round($g->avg('aht_seconds') ?? 0, 1),
                'csat' => round($g->avg('csat_score') ?? 0, 2),
                'conversions' => $g->sum('conversions'),
            ];
        })->sortByDesc('calls')->values();

        return view('reports.call-center', compact('summary', 'trendLabels', 'callsTrend', 'ahtTrend', 'csatTrend', 'byEmployee', 'from', 'to'));
    }

    public function audit(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }
        if ($request->filled('from')) {
            $query->where('occurred_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('occurred_at', '<=', $request->to . ' 23:59:59');
        }

        $logs = $query->orderBy('occurred_at', 'desc')->paginate(25);
        return view('reports.audit', compact('logs'));
    }
}

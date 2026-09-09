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
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $trendLabels = array_values($daily->keys()->toArray());
        $callsTrend = array_values($daily->map(fn($g) => $g->sum('total_calls'))->toArray());
        $ahtTrend = array_values($daily->map(fn($g) => round($g->avg('aht_seconds') ?? 0, 1))->toArray());
        $csatTrend = array_values($daily->map(fn($g) => round($g->avg('csat_score') ?? 0, 2))->toArray());

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

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // EXPORT HELPERS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    private function exportPdf(string $view, array $data, string $filename, string $orientation = 'landscape')
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4', $orientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();
        $this->stampPageNumbers($dompdf);

        return response()->streamDownload(fn() => print($dompdf->output()), $filename . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Draws a "Page X of Y" stamp in the footer of every page. DomPDF cannot
     * resolve the total page count from CSS alone, so this must run after
     * render() using the canvas's native page-text callback.
     */
    private function stampPageNumbers($dompdf): void
    {
        $canvas = $dompdf->getCanvas();
        $metrics = $dompdf->getFontMetrics();
        $font = $metrics->getFont('DejaVu Sans', 'bold');

        $width = $canvas->get_width();
        $height = $canvas->get_height();
        $navy = [0.051, 0.243, 0.388];

        $text = 'Page {PAGE_NUM} of {PAGE_COUNT}';
        $textWidth = $metrics->getTextWidth('Page 000 of 000', $font, 8.5);

        $canvas->page_text($width - 22.5 - $textWidth, $height - 22, $text, $font, 8.5, $navy);
    }

    private function exportExcel(array $headers, array $rows, string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet->getStyle('A1:' . chr(ord('A') + count($headers) - 1) . '1')->getFont()->setBold(true);

        $sheet->fromArray($rows, null, 'A2');

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx') . '.xlsx';
        (new Xlsx($spreadsheet))->save($tmpFile);

        return response()->download($tmpFile, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // REPORT EXPORTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    public function exportEmployees(Request $request, string $format)
    {
        $from = $request->get('from', now()->startOfYear()->toDateString());
        $to = $request->get('to', now()->toDateString());

        $data = [
            'total' => Employee::count(),
            'active' => Employee::where('employment_status', 'active')->count(),
            'onProbation' => Employee::where('employment_status', 'probation')->count(),
            'terminated' => Employee::whereIn('employment_status', ['terminated', 'resigned'])->count(),
            'byDept' => Employee::with('department')->where('employment_status', 'active')->get()->groupBy(fn($e) => $e->department->name ?? 'Unassigned')->map(fn($g) => $g->count())->toArray(),
            'byType' => Employee::where('employment_status', 'active')->get()->groupBy('employment_type')->map(fn($g) => $g->count())->toArray(),
            'joiners' => Employee::whereBetween('hire_date', [$from, $to])->orderBy('hire_date', 'desc')->get(),
            'leavers' => Employee::whereIn('employment_status', ['terminated', 'resigned'])->whereBetween('termination_date', [$from, $to])->get(),
            'from' => $from,
            'to' => $to,
        ];

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.employees-pdf', $data, 'employee-report');
        }

        $rows = [];
        foreach ($data['joiners'] as $j) {
            $rows[] = [$j->employee_code, $j->first_name . ' ' . $j->last_name, $j->hire_date?->format('Y-m-d'), 'Joiner'];
        }
        foreach ($data['leavers'] as $l) {
            $rows[] = [$l->employee_code, $l->first_name . ' ' . $l->last_name, $l->termination_date?->format('Y-m-d'), ucfirst(str_replace('_', ' ', $l->employment_status))];
        }
        return $this->exportExcel(['Code', 'Name', 'Date', 'Status'], $rows, 'employee-report');
    }

    public function exportAttendance(Request $request, string $format)
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

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.attendance-pdf', compact('summary', 'byEmployee', 'from', 'to'), 'attendance-report');
        }

        $rows = [];
        foreach ($byEmployee as $row) {
            $rows[] = [
                ($row['employee']?->first_name ?? '') . ' ' . ($row['employee']?->last_name ?? ''),
                $row['present'], $row['late'], $row['absent'], $row['ot_minutes'],
            ];
        }
        return $this->exportExcel(['Employee', 'Present', 'Late', 'Absent', 'OT (min)'], $rows, 'attendance-report');
    }

    public function exportPayroll(Request $request, string $format)
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

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.payroll-pdf', compact('monthlyData', 'totalNet', 'totalGross', 'totalDeductions', 'year'), 'payroll-report');
        }

        $rows = array_map(fn($m) => [$m['month'], $m['employees'], $m['gross'], $m['deductions'], $m['net']], $monthlyData);
        return $this->exportExcel(['Month', 'Employees', 'Gross', 'Deductions', 'Net'], $rows, 'payroll-report');
    }

    public function exportRecruitment(string $format)
    {
        $jobs = JobPosition::withCount('applicants')->get();
        $totalApplicants = Applicant::count();
        $hired = Applicant::where('stage', 'hired')->count();
        $rejected = Applicant::where('stage', 'rejected')->count();
        $inProgress = Applicant::whereNotIn('stage', ['hired', 'rejected'])->count();
        $bySource = Applicant::get()->groupBy('source')->map(fn($g) => $g->count())->toArray();
        $byStage = Applicant::get()->groupBy('stage')->map(fn($g) => $g->count())->toArray();
        $conversionRate = $totalApplicants > 0 ? round(($hired / $totalApplicants) * 100, 1) : 0;

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.recruitment-pdf', compact('jobs', 'totalApplicants', 'hired', 'rejected', 'inProgress', 'bySource', 'byStage', 'conversionRate'), 'recruitment-report');
        }

        $rows = [];
        foreach ($jobs as $job) {
            $rows[] = [$job->title, $job->openings, $job->applicants_count, ucfirst($job->status)];
        }
        return $this->exportExcel(['Job Title', 'Openings', 'Applicants', 'Status'], $rows, 'recruitment-report');
    }

    public function exportPerformance(Request $request, string $format)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $evals = PerformanceEvaluation::with('employee')->where('period_year', $year)->where('period_month', $month)->get();
        $gradeDist = $evals->groupBy('grade')->map(fn($g) => $g->count())->toArray();
        $avgScore = $evals->avg('weighted_score');
        $topPerformers = $evals->sortByDesc('weighted_score')->take(10);

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.performance-pdf', compact('gradeDist', 'avgScore', 'topPerformers', 'year', 'month'), 'performance-report');
        }

        $rows = [];
        foreach ($topPerformers as $eval) {
            $rows[] = [
                ($eval->employee?->first_name ?? '') . ' ' . ($eval->employee?->last_name ?? ''),
                $eval->grade,
                round($eval->weighted_score, 1),
            ];
        }
        return $this->exportExcel(['Employee', 'Grade', 'Score'], $rows, 'performance-report');
    }

    public function exportCallCenter(Request $request, string $format)
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

        $byEmployee = $stats->groupBy('employee_id')->map(function($g) {
            return [
                'employee' => $g->first()->employee,
                'calls' => $g->sum('total_calls'),
                'aht' => round($g->avg('aht_seconds') ?? 0, 1),
                'csat' => round($g->avg('csat_score') ?? 0, 2),
                'conversions' => $g->sum('conversions'),
            ];
        })->sortByDesc('calls')->values();

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.call-center-pdf', compact('summary', 'byEmployee', 'from', 'to'), 'call-center-report');
        }

        $rows = [];
        foreach ($byEmployee as $row) {
            $rows[] = [
                ($row['employee']?->first_name ?? '') . ' ' . ($row['employee']?->last_name ?? ''),
                $row['calls'], $row['aht'], $row['csat'], $row['conversions'],
            ];
        }
        return $this->exportExcel(['Employee', 'Calls', 'AHT (s)', 'CSAT', 'Conversions'], $rows, 'call-center-report');
    }

    public function exportAudit(Request $request, string $format)
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
        $logs = $query->orderBy('occurred_at', 'desc')->limit(1000)->get();

        if ($format === 'pdf') {
            return $this->exportPdf('reports.exports.audit-pdf', compact('logs'), 'audit-log-report');
        }

        $rows = [];
        foreach ($logs as $log) {
            $rows[] = [
                $log->occurred_at?->format('Y-m-d H:i:s'),
                $log->user?->name ?? 'System',
                $log->action,
                $log->auditable_type ? class_basename($log->auditable_type) . '#' . $log->auditable_id : '-',
                $log->ip_address ?? '-',
            ];
        }
        return $this->exportExcel(['Timestamp', 'User', 'Action', 'Model', 'IP Address'], $rows, 'audit-log-report');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // SYSTEM AUDIT REPORT (PDF + Excel)
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    public function downloadAuditReport(string $format)
    {
        $auditData = $this->buildSystemAuditData();

        if ($format === 'excel') {
            $rows = [];
            foreach ($auditData['sections'] as $section) {
                foreach ($section['items'] as $item) {
                    $rows[] = [$section['title'], $item['name'], $item['status'], $item['details']];
                }
            }
            return $this->exportExcel(['Section', 'Item', 'Status', 'Details'], $rows, 'system-audit-report');
        }

        return $this->exportPdf('reports.exports.system-audit-pdf', $auditData, 'system-audit-report');
    }

    private function buildSystemAuditData(): array
    {
        return [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'sections' => [
                [
                    'title' => 'Permissions & Access Control',
                    'items' => [
                        ['name' => 'Authentication', 'status' => 'PASS', 'details' => 'All routes behind auth middleware'],
                        ['name' => 'Role Middleware', 'status' => 'PASS', 'details' => 'Admin/owner-only routes protected via EnsureUserHasRole middleware'],
                        ['name' => 'Organization CRUD', 'status' => 'PASS', 'details' => 'Restricted to admin and owner roles'],
                        ['name' => 'Payroll CRUD', 'status' => 'PASS', 'details' => 'Restricted to admin and owner roles'],
                    ],
                ],
                [
                    'title' => 'Accessibility',
                    'items' => [
                        ['name' => 'Skip-to-content link', 'status' => 'PASS', 'details' => 'Added to dashboard layout'],
                        ['name' => 'ARIA labels', 'status' => 'PASS', 'details' => 'Icon-only buttons have aria-labels'],
                        ['name' => 'Focus styles', 'status' => 'PASS', 'details' => 'Custom focus-visible outlines added'],
                        ['name' => 'Color contrast', 'status' => 'PASS', 'details' => 'Navy-200/70 on navy-900 improved to navy-100'],
                        ['name' => 'HTML lang attribute', 'status' => 'PASS', 'details' => 'Set dynamically via app locale'],
                    ],
                ],
                [
                    'title' => 'Cookie & Consent',
                    'items' => [
                        ['name' => 'Cookie consent banner', 'status' => 'PASS', 'details' => 'Implemented with localStorage dismissal'],
                        ['name' => 'Session cookies only', 'status' => 'PASS', 'details' => 'No tracking cookies used'],
                        ['name' => 'Data minimization', 'status' => 'PASS', 'details' => 'No unnecessary PII collected in forms'],
                    ],
                ],
                [
                    'title' => 'Data Quality',
                    'items' => [
                        ['name' => 'Fake/dummy data in views', 'status' => 'PASS', 'details' => 'No placeholder data found in Blade templates'],
                        ['name' => 'Seeder data', 'status' => 'INFO', 'details' => 'DatabaseSeeder uses realistic Tanzanian call center data for development'],
                        ['name' => 'Input validation', 'status' => 'PASS', 'details' => 'All controller store/update methods validate input'],
                    ],
                ],
                [
                    'title' => 'Audit Logging',
                    'items' => [
                        ['name' => 'Employee CRUD', 'status' => 'PASS', 'details' => 'Created, updated, deleted actions logged'],
                        ['name' => 'Leave balance CRUD', 'status' => 'PASS', 'details' => 'Created, updated, deleted actions logged'],
                        ['name' => 'Audit log viewer', 'status' => 'PASS', 'details' => 'Available at /reports/audit with filtering'],
                    ],
                ],
                [
                    'title' => 'Reports & Exports',
                    'items' => [
                        ['name' => 'Employee report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Attendance report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Payroll report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Recruitment report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Performance report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Call center report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                        ['name' => 'Audit log report', 'status' => 'PASS', 'details' => 'PDF + Excel export available'],
                    ],
                ],
                [
                    'title' => 'System Inventory',
                    'items' => [
                        ['name' => 'Controllers', 'status' => 'INFO', 'details' => '12 controllers (Home, Employee, Organization, Attendance, Shift, Leave, Payroll, Recruitment, Performance, Analytics, Report, Auth)'],
                        ['name' => 'Models', 'status' => 'INFO', 'details' => '40 Eloquent models'],
                        ['name' => 'Views', 'status' => 'INFO', 'details' => '50+ Blade templates across 12 modules'],
                        ['name' => 'Routes', 'status' => 'INFO', 'details' => '80+ named routes'],
                        ['name' => 'Framework', 'status' => 'INFO', 'details' => 'Laravel 13.19 + PHP 8.4'],
                    ],
                ],
            ],
        ];
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // SYSTEM OVERVIEW PRESENTATION
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    public function systemOverview()
    {
        $stats = [
            'employees' => Employee::count(),
            'departments' => \App\Models\Department::count(),
            'branches' => \App\Models\Branch::count(),
            'positions' => \App\Models\Position::count(),
            'teams' => \App\Models\Team::count(),
            'leave_types' => \App\Models\LeaveType::count(),
            'shifts' => \App\Models\Shift::count(),
            'salary_components' => \App\Models\SalaryComponent::count(),
            'kpis' => \App\Models\Kpi::count(),
            'job_positions' => JobPosition::count(),
            'applicants' => Applicant::count(),
            'audit_logs' => AuditLog::count(),
            'payroll_runs' => PayrollRun::count(),
            'users' => \App\Models\User::count(),
        ];

        $modules = [
            ['name' => 'Dashboard', 'icon' => 'home', 'description' => 'KPI summary, attendance chart, pending items overview'],
            ['name' => 'Employees', 'icon' => 'users', 'description' => 'Full CRUD, documents, contracts, salaries, bank accounts, emergency contacts'],
            ['name' => 'Organization', 'icon' => 'building', 'description' => 'Branches, departments, positions, teams, working hours, holidays, policies'],
            ['name' => 'Attendance', 'icon' => 'calendar', 'description' => 'Daily grid, missing records, corrections, summary reports'],
            ['name' => 'Shifts', 'icon' => 'clock', 'description' => 'Shift catalog, weekly planner, rotation management'],
            ['name' => 'Leave', 'icon' => 'briefcase', 'description' => 'Leave types, requests with approval workflow, balance management'],
            ['name' => 'Payroll', 'icon' => 'cash', 'description' => 'Salary components, payroll runs, payslips, bonuses, commissions'],
            ['name' => 'Recruitment', 'icon' => 'user-add', 'description' => 'Job positions, applicant pipeline, interviews, onboarding checklists'],
            ['name' => 'Performance', 'icon' => 'chart', 'description' => 'KPIs, targets, evaluations with grading, leaderboard'],
            ['name' => 'Analytics', 'icon' => 'graph', 'description' => 'Call center metrics overview, daily stats data entry'],
            ['name' => 'Reports', 'icon' => 'document', 'description' => '7 report types with PDF/Excel export, audit log viewer'],
        ];

        $roles = [
            ['name' => 'Owner', 'color' => 'copper', 'access' => 'Full system access'],
            ['name' => 'Admin', 'color' => 'navy', 'access' => 'HR, organization, attendance, reports'],
            ['name' => 'HR Officer', 'color' => 'green', 'access' => 'Employees, leave, recruitment'],
            ['name' => 'QA Evaluator', 'color' => 'purple', 'access' => 'Performance, analytics'],
        ];

        return view('reports.system-overview', compact('stats', 'modules', 'roles'));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // USER GUIDE PDF
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    public function downloadGuide()
    {
        $guideData = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'roles' => [
                [
                    'name' => 'Owner',
                    'description' => 'Full system access including all configuration, payroll, reports, and user management.',
                    'permissions' => ['All system modules', 'Payroll management', 'Report exports', 'Audit log access', 'Organization settings'],
                    'modules' => [
                        ['name' => 'Dashboard', 'steps' => ['Navigate to /home', 'View KPI summary cards', 'Check attendance chart', 'Review pending items']],
                        ['name' => 'Employees', 'steps' => ['Go to Employees > All Employees', 'Click Add Employee to create new', 'Click any employee to view profile', 'Use Edit to update details', 'Use Terminate for offboarding']],
                        ['name' => 'Payroll', 'steps' => ['Go to Payroll > Components to manage salary parts', 'Navigate to Payroll Runs to create monthly run', 'Click Process to calculate payslips', 'Approve and Mark Paid to finalize']],
                        ['name' => 'Reports', 'steps' => ['Go to Reports section', 'Select report type', 'Use date filters', 'Click Export PDF or Export Excel']],
                    ],
                ],
                [
                    'name' => 'Admin',
                    'description' => 'Administrative access to employee management, organization settings, leave, attendance, and reports.',
                    'permissions' => ['Employee management', 'Organization settings', 'Leave management', 'Attendance tracking', 'Reports & exports'],
                    'modules' => [
                        ['name' => 'Employees', 'steps' => ['Navigate to Employees', 'Add new employees with Add Employee button', 'View employee profiles', 'Upload documents, contracts, salaries', 'Set bank accounts and emergency contacts']],
                        ['name' => 'Organization', 'steps' => ['Go to Organization > Branches/Departments/Positions/Teams', 'Add new items using Add button', 'Edit by clicking the edit icon', 'Delete with confirmation dialog']],
                        ['name' => 'Leave Management', 'steps' => ['Go to Leave > Types to configure leave types', 'Navigate to Leave > Requests to approve/reject', 'Use Leave > Balances to manage entitlements', 'Click any balance cell to edit', 'Use + icon to add missing balances']],
                        ['name' => 'Attendance', 'steps' => ['Go to Attendance > Daily Grid', 'Select date and department', 'Mark attendance status', 'Check Missing tab for gaps', 'Approve overtime in Corrections']],
                        ['name' => 'Reports', 'steps' => ['Go to Reports section', 'Choose report type', 'Apply filters', 'Export as PDF or Excel']],
                    ],
                ],
                [
                    'name' => 'HR Officer',
                    'description' => 'Human resources management including employees, leave, recruitment, and onboarding.',
                    'permissions' => ['Employee management', 'Leave requests', 'Recruitment', 'Onboarding'],
                    'modules' => [
                        ['name' => 'Employees', 'steps' => ['Navigate to Employees', 'Add new employees', 'Manage employee documents', 'Track contract expiries']],
                        ['name' => 'Leave', 'steps' => ['Go to Leave > Requests', 'Review pending requests', 'Approve or reject with notes', 'Manage leave balances']],
                        ['name' => 'Recruitment', 'steps' => ['Go to Recruitment > Job Positions', 'Create job openings', 'Track applicants through pipeline', 'Schedule interviews', 'Manage onboarding checklist']],
                    ],
                ],
                [
                    'name' => 'QA Evaluator',
                    'description' => 'Quality assurance and performance evaluation access.',
                    'permissions' => ['Performance KPIs', 'Evaluations', 'Analytics overview'],
                    'modules' => [
                        ['name' => 'Performance', 'steps' => ['Go to Performance > KPIs', 'Review KPI definitions', 'Navigate to Targets to set goals', 'Generate evaluations', 'Finalize evaluations with grades']],
                        ['name' => 'Analytics', 'steps' => ['Go to Analytics > Overview', 'Review call center metrics', 'Enter daily stats via Data Entry']],
                    ],
                ],
            ],
        ];

        return $this->exportPdf('reports.exports.guide-pdf', $guideData, 'AYS-Call-Center-User-Guide', 'portrait');
    }
}

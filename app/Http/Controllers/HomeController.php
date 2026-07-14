<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use App\Models\PayrollRun;
use App\Models\JobPosition;
use App\Models\EmployeeContract;
use App\Models\CoachingNote;
use App\Models\AgentDailyStat;
use App\Models\Department;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $today = now()->toDateString();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // KPI Stat Cards
        $totalAgents = Employee::where('employment_status', 'active')->count();
        $todayPresent = AttendanceRecord::where('date', $today)->whereIn('status', ['present', 'late'])->count();
        $todayTotal = AttendanceRecord::where('date', $today)->count();
        $todayAttendancePct = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100, 1) : 0;
        $missingAttendance = Employee::where('employment_status', 'active')
            ->whereNotIn('id', AttendanceRecord::where('date', $today)->pluck('employee_id'))
            ->count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Attendance trend (30 days)
        $attendanceDays = [];
        $attendanceLabels = [];
        $presentData = [];
        $absentData = [];
        $lateData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $attendanceLabels[] = $date->format('M d');
            $presentData[] = AttendanceRecord::where('date', $date->toDateString())->where('status', 'present')->count();
            $lateData[] = AttendanceRecord::where('date', $date->toDateString())->where('status', 'late')->count();
            $absentData[] = AttendanceRecord::where('date', $date->toDateString())->where('status', 'absent')->count();
        }

        // Headcount by department
        $departments = Department::withCount(['employees' => function($q) {
            $q->where('employment_status', 'active');
        }])->orderBy('employees_count', 'desc')->take(8)->get()->filter(fn($d) => $d->employees_count > 0);
        $deptLabels = $departments->pluck('name')->toArray();
        $deptCounts = $departments->pluck('employees_count')->toArray();

        // Payroll trend (12 months)
        $payrollMonths = [];
        $payrollLabels = [];
        $payrollNet = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $payrollLabels[] = $date->format('M Y');
            $run = PayrollRun::where('period_year', $date->year)->where('period_month', $date->month)->first();
            $payrollNet[] = $run ? (float) $run->total_net : 0;
        }

        // Payroll status stepper
        $currentPayroll = PayrollRun::where('period_year', $thisYear)->where('period_month', $thisMonth)->first();

        // Recruitment funnel
        $openJobs = JobPosition::where('status', 'open')->count();
        $applicantsApplied = \App\Models\Applicant::where('stage', 'applied')->count();
        $applicantsScreening = \App\Models\Applicant::where('stage', 'screening')->count();
        $applicantsInterview = \App\Models\Applicant::where('stage', 'interview')->count();
        $applicantsOffer = \App\Models\Applicant::where('stage', 'offer')->count();
        $applicantsHired = \App\Models\Applicant::where('stage', 'hired')->count();

        // Action center
        $contractsExpiring = EmployeeContract::where('status', 'active')
            ->where('end_date', '>=', $today)
            ->where('end_date', '<=', now()->addDays(30)->toDateString())
            ->count();
        $probationsEnding = Employee::where('employment_status', 'probation')
            ->where('probation_end_date', '<=', now()->addDays(30)->toDateString())
            ->count();
        $unapprovedOvertime = AttendanceRecord::where('overtime_minutes', '>', 0)->where('overtime_approved', false)->count();
        $overdueCoaching = CoachingNote::where('status', 'open')->where('follow_up_date', '<', $today)->count();

        // Performance sparkline (avg score per month)
        $perfMonths = [];
        $perfLabels = [];
        $perfScores = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $perfLabels[] = $date->format('M');
            $avgScore = \App\Models\PerformanceEvaluation::where('period_year', $date->year)
                ->where('period_month', $date->month)
                ->avg('weighted_score');
            $perfScores[] = $avgScore ? round($avgScore, 1) : 0;
        }

        // Call center analytics summary
        $totalCallsToday = AgentDailyStat::where('date', $today)->sum('total_calls');
        $avgAht = AgentDailyStat::where('date', $today)->avg('aht_seconds');
        $avgCsat = AgentDailyStat::where('date', $today)->avg('csat_score');
        $totalConversions = AgentDailyStat::where('date', $today)->sum('conversions');

        // Recent hires
        $recentHires = Employee::with(['department', 'position'])->orderBy('created_at', 'desc')->take(6)->get();

        return view('home', compact(
            'totalAgents', 'todayPresent', 'todayTotal', 'todayAttendancePct', 'missingAttendance', 'pendingLeaves',
            'attendanceLabels', 'presentData', 'absentData', 'lateData',
            'deptLabels', 'deptCounts',
            'payrollLabels', 'payrollNet', 'currentPayroll',
            'openJobs', 'applicantsApplied', 'applicantsScreening', 'applicantsInterview', 'applicantsOffer', 'applicantsHired',
            'contractsExpiring', 'probationsEnding', 'unapprovedOvertime', 'overdueCoaching',
            'perfLabels', 'perfScores',
            'totalCallsToday', 'avgAht', 'avgCsat', 'totalConversions',
            'recentHires'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceCorrection;
use App\Models\Employee;
use App\Models\ShiftAssignment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $departmentId = $request->get('department_id');

        $query = Employee::with(['department', 'position', 'shiftAssignments' => function($q) use ($date) {
            $q->where('date', $date)->with('shift');
        }])->where('employment_status', 'active');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->orderBy('first_name')->get();
        $departments = \App\Models\Department::where('is_active', true)->get();

        $records = AttendanceRecord::where('date', $date)->get()->keyBy('employee_id');

        return view('attendance.index', compact('employees', 'departments', 'date', 'records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day,on_leave,holiday,off',
            'remarks' => 'nullable|string',
        ]);

        $checkIn = $validated['check_in'] ? $validated['date'] . ' ' . $validated['check_in'] . ':00' : null;
        $checkOut = $validated['check_out'] ? $validated['date'] . ' ' . $validated['check_out'] . ':00' : null;

        $workedMinutes = 0;
        $lateMinutes = 0;
        if ($checkIn && $checkOut) {
            $workedMinutes = (strtotime($checkOut) - strtotime($checkIn)) / 60;
        }

        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            [
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $validated['status'],
                'worked_minutes' => (int) $workedMinutes,
                'late_minutes' => $validated['status'] === 'late' ? $lateMinutes : 0,
                'remarks' => $validated['remarks'],
                'recorded_by' => auth()->id(),
                'source' => 'manual',
            ]
        );

        return response()->json(['success' => true, 'message' => 'Attendance saved.']);
    }

    public function bulkMark(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent,off',
            'employee_ids' => 'required|array',
        ]);

        foreach ($validated['employee_ids'] as $empId) {
            AttendanceRecord::updateOrCreate(
                ['employee_id' => $empId, 'date' => $validated['date']],
                [
                    'status' => $validated['status'],
                    'recorded_by' => auth()->id(),
                    'source' => 'bulk_import',
                ]
            );
        }

        return response()->json(['success' => true, 'message' => count($validated['employee_ids']) . ' records marked.']);
    }

    public function missing(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $presentIds = AttendanceRecord::where('date', $date)->pluck('employee_id');
        $missing = Employee::with(['department', 'position'])
            ->where('employment_status', 'active')
            ->whereNotIn('id', $presentIds)
            ->get();

        return view('attendance.missing', compact('missing', 'date'));
    }

    public function corrections()
    {
        $corrections = AttendanceCorrection::with(['record.employee', 'correctedBy'])
            ->orderBy('corrected_at', 'desc')
            ->paginate(20);

        return view('attendance.corrections', compact('corrections'));
    }

    public function approveOvertime(AttendanceRecord $record)
    {
        $record->update(['overtime_approved' => true]);
        return back()->with('success', 'Overtime approved.');
    }

    public function summary(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->endOfMonth()->toDateString());

        $records = AttendanceRecord::with('employee')
            ->whereBetween('date', [$from, $to])
            ->get();

        $summary = [
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
            'off' => $records->where('status', 'off')->count(),
            'total_ot_minutes' => $records->sum('overtime_minutes'),
            'avg_late_minutes' => round($records->avg('late_minutes'), 1),
        ];

        $byEmployee = $records->groupBy('employee_id')->map(function($group, $empId) {
            return [
                'employee' => $group->first()->employee,
                'present' => $group->where('status', 'present')->count(),
                'late' => $group->where('status', 'late')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'ot_minutes' => $group->sum('overtime_minutes'),
            ];
        })->values();

        return view('attendance.summary', compact('summary', 'byEmployee', 'from', 'to'));
    }
}

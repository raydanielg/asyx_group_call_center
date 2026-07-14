<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    use AjaxResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function typesIndex()
    {
        $types = LeaveType::orderBy('name')->paginate(15);
        return view('leave.types', compact('types'));
    }

    public function typesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:leave_types,code',
            'days_per_year' => 'required|numeric|min:0',
            'is_paid' => 'boolean',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'nullable|numeric|min:0',
            'requires_attachment' => 'boolean',
            'gender_restriction' => 'required|in:any,male,female',
        ]);
        LeaveType::create($validated);
        return $this->ajaxSuccess('Leave type created successfully.');
    }

    public function typesUpdate(Request $request, LeaveType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:leave_types,code,' . $type->id,
            'days_per_year' => 'required|numeric|min:0',
            'is_paid' => 'boolean',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'nullable|numeric|min:0',
            'requires_attachment' => 'boolean',
            'gender_restriction' => 'required|in:any,male,female',
            'is_active' => 'boolean',
        ]);
        $type->update($validated);
        return $this->ajaxSuccess('Leave type updated successfully.');
    }

    public function typesDestroy(LeaveType $type)
    {
        $type->delete();
        return $this->ajaxSuccess('Leave type deleted successfully.');
    }

    public function requestsIndex(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'leaveType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);
        $employees = Employee::where('employment_status', 'active')->get();
        $types = LeaveType::where('is_active', true)->get();

        return view('leave.requests', compact('requests', 'employees', 'types'));
    }

    public function requestsStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'half_day' => 'nullable|in:none,am,pm',
            'reason' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start->diffInDays($end) + 1;
        if ($validated['half_day'] !== 'none') {
            $days = 0.5;
        }

        LeaveRequest::create(array_merge($validated, [
            'days' => $days,
            'status' => 'pending',
            'recorded_by' => auth()->id(),
        ]));

        return $this->ajaxSuccess('Leave request recorded successfully.');
    }

    public function requestsApprove(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'decided_by' => auth()->id(),
            'decided_at' => now(),
        ]);

        // Update balance
        $balance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $leaveRequest->employee_id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'year' => now()->year,
            ],
            ['entitled' => $leaveRequest->leaveType->days_per_year, 'carried_over' => 0, 'used' => 0]
        );
        $balance->increment('used', $leaveRequest->days);

        // Create attendance record
        \App\Models\AttendanceRecord::updateOrCreate(
            ['employee_id' => $leaveRequest->employee_id, 'date' => $leaveRequest->start_date],
            ['status' => 'on_leave', 'recorded_by' => auth()->id(), 'source' => 'manual']
        );

        return $this->ajaxSuccess('Leave request approved successfully.');
    }

    public function requestsReject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate(['decision_note' => 'required|string']);
        $leaveRequest->update([
            'status' => 'rejected',
            'decided_by' => auth()->id(),
            'decided_at' => now(),
            'decision_note' => $validated['decision_note'],
        ]);
        return $this->ajaxSuccess('Leave request rejected successfully.');
    }

    public function requestsCancel(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status === 'approved') {
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)
                ->where('year', now()->year)
                ->first();
            if ($balance) {
                $balance->decrement('used', $leaveRequest->days);
            }
        }

        $leaveRequest->update(['status' => 'cancelled']);
        return $this->ajaxSuccess('Leave request cancelled successfully.');
    }

    public function balances(Request $request)
    {
        $year = $request->get('year', now()->year);
        $employees = Employee::with(['leaveBalances' => function($q) use ($year) {
            $q->where('year', $year)->with('leaveType');
        }])->where('employment_status', 'active')->orderBy('first_name')->paginate(20);
        $types = LeaveType::where('is_active', true)->get();

        return view('leave.balances', compact('employees', 'types', 'year'));
    }

    public function employeeHistory(Request $request, Employee $employee)
    {
        $query = LeaveRequest::with(['leaveType', 'decidedBy'])
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15);
        $types = LeaveType::where('is_active', true)->get();

        $year = now()->year;
        $balances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->where('year', $year)
            ->get();

        $stats = [
            'total' => LeaveRequest::where('employee_id', $employee->id)->count(),
            'pending' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
        ];

        return view('leave.history', compact('employee', 'requests', 'types', 'balances', 'stats', 'year'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\ShiftRotation;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use AjaxResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $shifts = Shift::orderBy('start_time')->paginate(15);
        return view('shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:shifts,code',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'crosses_midnight' => 'boolean',
            'break_minutes' => 'nullable|integer|min:0',
            'grace_minutes' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:7',
            'is_night_shift' => 'boolean',
            'night_allowance' => 'nullable|numeric|min:0',
        ]);

        $start = strtotime($validated['start_time']);
        $end = strtotime($validated['end_time']);
        $validated['crosses_midnight'] = $end < $start;

        Shift::create($validated);
        return $this->ajaxSuccess('Shift created successfully.');
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:shifts,code,' . $shift->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'crosses_midnight' => 'boolean',
            'break_minutes' => 'nullable|integer|min:0',
            'grace_minutes' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:7',
            'is_night_shift' => 'boolean',
            'night_allowance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $shift->update($validated);
        return $this->ajaxSuccess('Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return $this->ajaxSuccess('Shift deleted successfully.');
    }

    public function planner(Request $request)
    {
        $weekStart = $request->get('week', now()->startOfWeek()->toDateString());
        $start = \Carbon\Carbon::parse($weekStart)->startOfWeek();
        $days = [];
        $dayStrings = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);
            $days[] = $day;
            $dayStrings[] = $day->toDateString();
        }

        $prevDays = [];
        for ($i = 0; $i < 7; $i++) {
            $prevDays[] = $start->copy()->subWeek()->addDays($i)->toDateString();
        }

        $shifts = Shift::where('is_active', true)->get();
        $employees = Employee::with(['shiftAssignments' => function($q) use ($dayStrings) {
            $q->whereIn('date', $dayStrings)->with('shift');
        }])->where('employment_status', 'active')->orderBy('first_name')->get();

        $assignments = ShiftAssignment::with(['employee', 'shift'])
            ->whereIn('date', $dayStrings)
            ->get()
            ->keyBy(function($a) { return $a->employee_id . '-' . $a->date; });

        $prevAssignments = ShiftAssignment::with(['shift'])
            ->whereIn('date', $prevDays)
            ->get()
            ->groupBy('employee_id');

        return view('shifts.planner', compact('days', 'dayStrings', 'prevDays', 'shifts', 'employees', 'assignments', 'prevAssignments', 'start'));
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'date' => 'required|date',
        ]);

        ShiftAssignment::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            [
                'shift_id' => $validated['shift_id'],
                'assigned_by' => auth()->id(),
            ]
        );

        return $this->ajaxSuccess('Shift assigned successfully.');
    }

    public function unassign(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        ShiftAssignment::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->delete();

        return $this->ajaxSuccess('Shift unassigned successfully.');
    }

    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'dates' => 'required|array',
            'dates.*' => 'required|date',
        ]);

        foreach ($validated['dates'] as $date) {
            ShiftAssignment::updateOrCreate(
                ['employee_id' => $validated['employee_id'], 'date' => $date],
                [
                    'shift_id' => $validated['shift_id'],
                    'assigned_by' => auth()->id(),
                ]
            );
        }

        return $this->ajaxSuccess('Shift assigned to ' . count($validated['dates']) . ' day(s) successfully.');
    }

    public function copyWeek(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'source_dates' => 'required|array',
            'source_dates.*' => 'required|date',
            'target_dates' => 'required|array',
            'target_dates.*' => 'required|date',
        ]);

        $sourceAssignments = ShiftAssignment::where('employee_id', $validated['employee_id'])
            ->whereIn('date', $validated['source_dates'])
            ->get()
            ->keyBy(fn($a) => $a->date->toDateString());

        $copied = 0;
        foreach ($validated['source_dates'] as $i => $srcDate) {
            if (isset($sourceAssignments[$srcDate]) && isset($validated['target_dates'][$i])) {
                ShiftAssignment::updateOrCreate(
                    ['employee_id' => $validated['employee_id'], 'date' => $validated['target_dates'][$i]],
                    [
                        'shift_id' => $sourceAssignments[$srcDate]->shift_id,
                        'assigned_by' => auth()->id(),
                    ]
                );
                $copied++;
            }
        }

        return $this->ajaxSuccess($copied . ' shift(s) copied from previous week.');
    }

    public function clearWeek(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'dates' => 'required|array',
            'dates.*' => 'required|date',
        ]);

        $deleted = ShiftAssignment::where('employee_id', $validated['employee_id'])
            ->whereIn('date', $validated['dates'])
            ->delete();

        return $this->ajaxSuccess($deleted . ' shift(s) cleared.');
    }

    public function rotations()
    {
        $rotations = ShiftRotation::with(['team', 'department'])->orderBy('name')->paginate(15);
        $shifts = Shift::where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();
        $teams = \App\Models\Team::where('is_active', true)->get();
        return view('shifts.rotations', compact('rotations', 'shifts', 'departments', 'teams'));
    }

    public function rotationsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'pattern' => 'required|json',
            'cycle_days' => 'required|integer|min:1',
            'applies_to' => 'required|in:team,department,employees',
            'team_id' => 'nullable|exists:teams,id',
            'department_id' => 'nullable|exists:departments,id',
            'starts_on' => 'required|date',
        ]);

        ShiftRotation::create($validated);
        return $this->ajaxSuccess('Rotation created successfully.');
    }

    public function rotationsDestroy(ShiftRotation $rotation)
    {
        $rotation->delete();
        return $this->ajaxSuccess('Rotation deleted successfully.');
    }
}

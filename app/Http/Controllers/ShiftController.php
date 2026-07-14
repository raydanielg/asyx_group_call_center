<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\ShiftRotation;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
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
            'color' => 'nullable|string|max:7',
            'is_night_shift' => 'boolean',
            'night_allowance' => 'nullable|numeric|min:0',
        ]);

        $start = strtotime($validated['start_time']);
        $end = strtotime($validated['end_time']);
        $validated['crosses_midnight'] = $end < $start;

        Shift::create($validated);
        return back()->with('success', 'Shift created.');
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
            'color' => 'nullable|string|max:7',
            'is_night_shift' => 'boolean',
            'night_allowance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $shift->update($validated);
        return back()->with('success', 'Shift updated.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return back()->with('success', 'Shift deleted.');
    }

    public function planner(Request $request)
    {
        $weekStart = $request->get('week', now()->startOfWeek()->toDateString());
        $start = \Carbon\Carbon::parse($weekStart);
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $start->copy()->addDays($i)->toDateString();
        }

        $shifts = Shift::where('is_active', true)->get();
        $employees = Employee::with(['shiftAssignments' => function($q) use ($days) {
            $q->whereIn('date', $days)->with('shift');
        }])->where('employment_status', 'active')->orderBy('first_name')->get();

        $assignments = ShiftAssignment::with(['employee', 'shift'])
            ->whereIn('date', $days)
            ->get()
            ->groupBy(function($a) { return $a->employee_id . '-' . $a->date; });

        return view('shifts.planner', compact('days', 'shifts', 'employees', 'assignments', 'weekStart'));
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

        return response()->json(['success' => true, 'message' => 'Shift assigned.']);
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

        return response()->json(['success' => true, 'message' => 'Shift unassigned.']);
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
        return back()->with('success', 'Rotation created.');
    }

    public function rotationsDestroy(ShiftRotation $rotation)
    {
        $rotation->delete();
        return back()->with('success', 'Rotation deleted.');
    }
}

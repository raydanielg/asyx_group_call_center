<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use App\Models\Team;
use App\Models\WorkingHourPolicy;
use App\Models\Holiday;
use App\Models\CompanyPolicy;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Branches
    public function branchesIndex()
    {
        $branches = Branch::orderBy('name')->paginate(15);
        return view('organization.branches', compact('branches'));
    }

    public function branchesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:branches,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'timezone' => 'nullable|string|max:64',
        ]);
        Branch::create($validated);
        return back()->with('success', 'Branch created.');
    }

    public function branchesUpdate(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'timezone' => 'nullable|string|max:64',
            'is_active' => 'boolean',
        ]);
        $branch->update($validated);
        return back()->with('success', 'Branch updated.');
    }

    public function branchesDestroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', 'Branch deleted.');
    }

    // Departments
    public function departmentsIndex()
    {
        $departments = Department::with(['branch', 'parent', 'positions'])->orderBy('name')->paginate(15);
        $branches = Branch::where('is_active', true)->get();
        $allDepts = Department::where('is_active', true)->get();
        return view('organization.departments', compact('departments', 'branches', 'allDepts'));
    }

    public function departmentsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:departments,code',
            'branch_id' => 'nullable|exists:branches,id',
            'parent_id' => 'nullable|exists:departments,id',
        ]);
        Department::create($validated);
        return back()->with('success', 'Department created.');
    }

    public function departmentsUpdate(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:departments,code,' . $department->id,
            'branch_id' => 'nullable|exists:branches,id',
            'parent_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);
        $department->update($validated);
        return back()->with('success', 'Department updated.');
    }

    public function departmentsDestroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }

    // Positions
    public function positionsIndex()
    {
        $positions = Position::with('department')->orderBy('title')->paginate(15);
        $departments = Department::where('is_active', true)->get();
        return view('organization.positions', compact('positions', 'departments'));
    }

    public function positionsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'code' => 'required|string|max:30|unique:positions,code',
            'department_id' => 'required|exists:departments,id',
            'level' => 'required|in:agent,senior_agent,team_lead,supervisor,manager',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);
        Position::create($validated);
        return back()->with('success', 'Position created.');
    }

    public function positionsUpdate(Request $request, Position $position)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'code' => 'required|string|max:30|unique:positions,code,' . $position->id,
            'department_id' => 'required|exists:departments,id',
            'level' => 'required|in:agent,senior_agent,team_lead,supervisor,manager',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        $position->update($validated);
        return back()->with('success', 'Position updated.');
    }

    public function positionsDestroy(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Position deleted.');
    }

    // Teams
    public function teamsIndex()
    {
        $teams = Team::with(['department', 'employees'])->orderBy('name')->paginate(15);
        $departments = Department::where('is_active', true)->get();
        return view('organization.teams', compact('teams', 'departments'));
    }

    public function teamsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
        ]);
        Team::create($validated);
        return back()->with('success', 'Team created.');
    }

    public function teamsUpdate(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ]);
        $team->update($validated);
        return back()->with('success', 'Team updated.');
    }

    public function teamsDestroy(Team $team)
    {
        $team->delete();
        return back()->with('success', 'Team deleted.');
    }

    // Working Hours
    public function workingHoursIndex()
    {
        $policies = WorkingHourPolicy::orderBy('name')->paginate(15);
        return view('organization.working-hours', compact('policies'));
    }

    public function workingHoursStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'hours_per_day' => 'required|numeric|min:0|max:24',
            'days_per_week' => 'required|integer|min:1|max:7',
            'week_start' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'grace_minutes' => 'required|integer|min:0|max:120',
            'overtime_after_minutes' => 'required|integer|min:0',
            'is_default' => 'boolean',
        ]);
        if ($validated['is_default'] ?? false) {
            WorkingHourPolicy::where('is_default', true)->update(['is_default' => false]);
        }
        WorkingHourPolicy::create($validated);
        return back()->with('success', 'Working hour policy created.');
    }

    public function workingHoursUpdate(Request $request, WorkingHourPolicy $policy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'hours_per_day' => 'required|numeric|min:0|max:24',
            'days_per_week' => 'required|integer|min:1|max:7',
            'week_start' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'grace_minutes' => 'required|integer|min:0|max:120',
            'overtime_after_minutes' => 'required|integer|min:0',
            'is_default' => 'boolean',
        ]);
        if ($validated['is_default'] ?? false) {
            WorkingHourPolicy::where('is_default', true)->where('id', '!=', $policy->id)->update(['is_default' => false]);
        }
        $policy->update($validated);
        return back()->with('success', 'Working hour policy updated.');
    }

    public function workingHoursDestroy(WorkingHourPolicy $policy)
    {
        $policy->delete();
        return back()->with('success', 'Policy deleted.');
    }

    // Holidays
    public function holidaysIndex()
    {
        $holidays = Holiday::with('branch')->orderBy('date')->paginate(15);
        $branches = Branch::where('is_active', true)->get();
        return view('organization.holidays', compact('holidays', 'branches'));
    }

    public function holidaysStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'date' => 'required|date',
            'is_recurring' => 'boolean',
            'branch_id' => 'nullable|exists:branches,id',
        ]);
        Holiday::create($validated);
        return back()->with('success', 'Holiday created.');
    }

    public function holidaysDestroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday deleted.');
    }

    // Company Policies
    public function policiesIndex()
    {
        $policies = CompanyPolicy::orderBy('title')->paginate(15);
        return view('organization.policies', compact('policies'));
    }

    public function policiesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|in:hr,conduct,leave,payroll,other',
            'body' => 'nullable|string',
            'effective_from' => 'nullable|date',
        ]);
        CompanyPolicy::create($validated);
        return back()->with('success', 'Policy created.');
    }

    public function policiesUpdate(Request $request, CompanyPolicy $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|in:hr,conduct,leave,payroll,other',
            'body' => 'nullable|string',
            'effective_from' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        $policy->update($validated);
        return back()->with('success', 'Policy updated.');
    }

    public function policiesDestroy(CompanyPolicy $policy)
    {
        $policy->delete();
        return back()->with('success', 'Policy deleted.');
    }
}

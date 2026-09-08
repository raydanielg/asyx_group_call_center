<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use App\Models\Team;
use App\Models\WorkingHourPolicy;
use App\Models\Holiday;
use App\Models\CompanyPolicy;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    use AjaxResponseTrait;

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
        $branch = Branch::create($validated);
        AuditLog::log('branch.created', $branch);
        return $this->ajaxSuccess('Branch created successfully.');
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
        $old = $branch->toArray();
        $branch->update($validated);
        AuditLog::log('branch.updated', $branch, $old, $validated);
        return $this->ajaxSuccess('Branch updated successfully.');
    }

    public function branchesDestroy(Branch $branch)
    {
        $old = $branch->toArray();
        $branch->delete();
        AuditLog::log('branch.deleted', null, $old);
        return $this->ajaxSuccess('Branch deleted successfully.');
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
        $dept = Department::create($validated);
        AuditLog::log('department.created', $dept);
        return $this->ajaxSuccess('Department created successfully.');
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
        $old = $department->toArray();
        $department->update($validated);
        AuditLog::log('department.updated', $department, $old, $validated);
        return $this->ajaxSuccess('Department updated successfully.');
    }

    public function departmentsDestroy(Department $department)
    {
        $old = $department->toArray();
        $department->delete();
        AuditLog::log('department.deleted', null, $old);
        return $this->ajaxSuccess('Department deleted successfully.');
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
        $pos = Position::create($validated);
        AuditLog::log('position.created', $pos);
        return $this->ajaxSuccess('Position created successfully.');
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
        $old = $position->toArray();
        $position->update($validated);
        AuditLog::log('position.updated', $position, $old, $validated);
        return $this->ajaxSuccess('Position updated successfully.');
    }

    public function positionsDestroy(Position $position)
    {
        $old = $position->toArray();
        $position->delete();
        AuditLog::log('position.deleted', null, $old);
        return $this->ajaxSuccess('Position deleted successfully.');
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
        $team = Team::create($validated);
        AuditLog::log('team.created', $team);
        return $this->ajaxSuccess('Team created successfully.');
    }

    public function teamsUpdate(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ]);
        $old = $team->toArray();
        $team->update($validated);
        AuditLog::log('team.updated', $team, $old, $validated);
        return $this->ajaxSuccess('Team updated successfully.');
    }

    public function teamsDestroy(Team $team)
    {
        $old = $team->toArray();
        $team->delete();
        AuditLog::log('team.deleted', null, $old);
        return $this->ajaxSuccess('Team deleted successfully.');
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
        return $this->ajaxSuccess('Working hour policy created successfully.');
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
        return $this->ajaxSuccess('Working hour policy updated successfully.');
    }

    public function workingHoursDestroy(WorkingHourPolicy $policy)
    {
        $policy->delete();
        return $this->ajaxSuccess('Working hour policy deleted successfully.');
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
        return $this->ajaxSuccess('Holiday created successfully.');
    }

    public function holidaysDestroy(Holiday $holiday)
    {
        $holiday->delete();
        return $this->ajaxSuccess('Holiday deleted successfully.');
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
        return $this->ajaxSuccess('Policy created successfully.');
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
        return $this->ajaxSuccess('Policy updated successfully.');
    }

    public function policiesDestroy(CompanyPolicy $policy)
    {
        $policy->delete();
        return $this->ajaxSuccess('Company policy deleted successfully.');
    }
}

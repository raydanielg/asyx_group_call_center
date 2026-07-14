<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use App\Models\Team;
use App\Models\EmployeeDocument;
use App\Models\EmployeeContract;
use App\Models\EmployeeSalary;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeEmergencyContact;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use AjaxResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'team', 'branch']);

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('first_name', 'like', "%{$q}%")
                   ->orWhere('last_name', 'like', "%{$q}%")
                   ->orWhere('employee_code', 'like', "%{$q}%")
                   ->orWhere('phone', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);
        $departments = Department::where('is_active', true)->get();
        $teams = Team::where('is_active', true)->get();

        return view('employees.index', compact('employees', 'departments', 'teams'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $teams = Team::where('is_active', true)->get();
        $managers = Employee::where('employment_status', 'active')->get();

        return view('employees.create', compact('branches', 'departments', 'positions', 'teams', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'middle_name' => 'nullable|string|max:80',
            'last_name' => 'required|string|max:80',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'national_id' => 'nullable|string|max:60',
            'email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'team_id' => 'nullable|exists:teams,id',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date',
            'probation_end_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'reports_to' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
            'base_salary' => 'nullable|numeric|min:0',
            'pay_frequency' => 'nullable|in:monthly,biweekly,weekly',
        ]);

        $code = 'EMP-' . str_pad(Employee::max('id') + 1, 4, '0', STR_PAD_LEFT);

        $employee = Employee::create(array_merge($validated, [
            'employee_code' => $code,
            'employment_status' => $request->boolean('probation_end_date') ? 'probation' : 'active',
            'created_by' => auth()->id(),
        ]));

        if ($request->filled('base_salary')) {
            EmployeeSalary::create([
                'employee_id' => $employee->id,
                'effective_from' => $validated['hire_date'],
                'base_salary' => $request->base_salary,
                'pay_frequency' => $request->pay_frequency ?? 'monthly',
                'created_by' => auth()->id(),
            ]);
        }

        AuditLog::log('employee.created', $employee);

        return $this->ajaxSuccess('Employee created successfully!', route('employees.show', $employee->id));
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'team', 'branch', 'reportsTo',
            'emergencyContacts', 'bankAccounts', 'documents', 'contracts', 'salaries',
            'attendanceRecords' => function($q) { $q->orderBy('date', 'desc')->take(30); },
            'leaveRequests' => function($q) { $q->orderBy('created_at', 'desc')->take(10); },
            'payslips' => function($q) { $q->orderBy('created_at', 'desc')->take(6); },
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $branches = Branch::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $teams = Team::where('is_active', true)->get();
        $managers = Employee::where('employment_status', 'active')->where('id', '!=', $employee->id)->get();

        return view('employees.edit', compact('employee', 'branches', 'departments', 'positions', 'teams', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'middle_name' => 'nullable|string|max:80',
            'last_name' => 'required|string|max:80',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'national_id' => 'nullable|string|max:60',
            'email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'team_id' => 'nullable|exists:teams,id',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date',
            'probation_end_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'employment_status' => 'required|in:active,probation,suspended,on_leave,terminated,resigned',
            'reports_to' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        $old = $employee->toArray();
        $employee->update($validated);
        AuditLog::log('employee.updated', $employee, $old, $validated);

        return $this->ajaxSuccess('Employee updated successfully!', route('employees.show', $employee->id));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        AuditLog::log('employee.deleted', $employee);
        return $this->ajaxSuccess('Employee deleted successfully.', route('employees.index'));
    }

    public function terminate(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'termination_date' => 'required|date',
            'termination_reason' => 'required|string',
        ]);

        $employee->update([
            'employment_status' => 'terminated',
            'termination_date' => $validated['termination_date'],
            'termination_reason' => $validated['termination_reason'],
        ]);

        AuditLog::log('employee.terminated', $employee);
        return $this->ajaxSuccess('Employee terminated successfully.', route('employees.show', $employee->id));
    }

    public function storeDocument(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'category' => 'required|in:contract,cv,certificate,id_copy,warning_letter,other',
            'title' => 'required|string|max:200',
            'expires_at' => 'nullable|date',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('employee-docs', 'public');
        }

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'file_path' => $path,
            'file_size' => $request->file('file')?->getSize(),
            'mime_type' => $request->file('file')?->getMimeType(),
            'uploaded_by' => auth()->id(),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return $this->ajaxSuccess('Document uploaded successfully.');
    }

    public function storeContract(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:permanent,fixed_term,probation',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
        ]);

        EmployeeContract::create(array_merge($validated, ['employee_id' => $employee->id, 'status' => 'active']));
        AuditLog::log('employee.contract.added', $employee);
        return $this->ajaxSuccess('Contract added successfully.');
    }

    public function storeSalary(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'effective_from' => 'required|date',
            'base_salary' => 'required|numeric|min:0',
            'pay_frequency' => 'required|in:monthly,biweekly,weekly',
        ]);

        EmployeeSalary::create(array_merge($validated, [
            'employee_id' => $employee->id,
            'created_by' => auth()->id(),
        ]));

        AuditLog::log('employee.salary.updated', $employee);
        return $this->ajaxSuccess('Salary record added successfully.');
    }

    public function storeBankAccount(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'branch' => 'nullable|string|max:100',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'is_primary' => 'boolean',
            'mobile_money_provider' => 'nullable|string|max:50',
            'mobile_money_number' => 'nullable|string|max:30',
        ]);

        if ($validated['is_primary'] ?? false) {
            $employee->bankAccounts()->update(['is_primary' => false]);
        }

        EmployeeBankAccount::create(array_merge($validated, ['employee_id' => $employee->id]));
        return $this->ajaxSuccess('Bank account added successfully.');
    }

    public function storeEmergencyContact(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'relationship' => 'required|string|max:50',
            'phone' => 'required|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        EmployeeEmergencyContact::create(array_merge($validated, ['employee_id' => $employee->id]));
        return $this->ajaxSuccess('Emergency contact added successfully.');
    }

    public function documentsExpiry()
    {
        $expiringDocs = EmployeeDocument::with('employee')
            ->where('expires_at', '>=', now()->toDateString())
            ->where('expires_at', '<=', now()->addDays(60)->toDateString())
            ->orderBy('expires_at')
            ->get();

        return view('employees.documents-expiry', compact('expiringDocs'));
    }
}

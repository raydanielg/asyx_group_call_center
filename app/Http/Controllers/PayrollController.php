<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\AttendanceRecord;
use App\Models\Bonus;
use App\Models\Commission;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function componentsIndex()
    {
        $components = SalaryComponent::orderBy('name')->paginate(15);
        return view('payroll.components', compact('components'));
    }

    public function componentsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:salary_components,code',
            'type' => 'required|in:allowance,deduction',
            'calc_type' => 'required|in:fixed,percent_of_basic,formula',
            'value' => 'required|numeric|min:0',
            'is_taxable' => 'boolean',
            'is_statutory' => 'boolean',
        ]);
        SalaryComponent::create($validated);
        return back()->with('success', 'Salary component created.');
    }

    public function componentsUpdate(Request $request, SalaryComponent $component)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:salary_components,code,' . $component->id,
            'type' => 'required|in:allowance,deduction',
            'calc_type' => 'required|in:fixed,percent_of_basic,formula',
            'value' => 'required|numeric|min:0',
            'is_taxable' => 'boolean',
            'is_statutory' => 'boolean',
            'is_active' => 'boolean',
        ]);
        $component->update($validated);
        return back()->with('success', 'Salary component updated.');
    }

    public function componentsDestroy(SalaryComponent $component)
    {
        $component->delete();
        return back()->with('success', 'Component deleted.');
    }

    public function runsIndex()
    {
        $runs = PayrollRun::with('processedBy')->orderBy('period_year', 'desc')->orderBy('period_month', 'desc')->paginate(15);
        return view('payroll.runs', compact('runs'));
    }

    public function runsStore(Request $request)
    {
        $validated = $request->validate([
            'period_year' => 'required|integer|min:2020|max:2050',
            'period_month' => 'required|integer|min:1|max:12',
        ]);

        $exists = PayrollRun::where('period_year', $validated['period_year'])
            ->where('period_month', $validated['period_month'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Payroll run already exists for this period.');
        }

        $run = PayrollRun::create([
            'period_year' => $validated['period_year'],
            'period_month' => $validated['period_month'],
            'status' => 'draft',
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('payroll.runs.show', $run->id)->with('success', 'Payroll run created. Click Process to calculate.');
    }

    public function runsShow(PayrollRun $run)
    {
        $run->load(['payslips.employee.department', 'payslips.employee.position', 'processedBy']);
        return view('payroll.run-show', compact('run'));
    }

    public function runsProcess(PayrollRun $run)
    {
        if ($run->status !== 'draft') {
            return back()->with('error', 'Run already processed.');
        }

        $run->update(['status' => 'processing']);

        $employees = Employee::where('employment_status', 'active')->get();
        $components = SalaryComponent::where('is_active', true)->get();
        $allowances = $components->where('type', 'allowance');
        $deductions = $components->where('type', 'deduction');

        $totalGross = 0;
        $totalDeductions = 0;
        $totalNet = 0;

        foreach ($employees as $employee) {
            $salary = $employee->currentSalary();
            $basicSalary = $salary ? $salary->base_salary : 0;

            $totalAllowance = 0;
            $totalDeduction = 0;
            $lines = [];

            foreach ($allowances as $allow) {
                $amount = $allow->calc_type === 'percent_of_basic'
                    ? ($basicSalary * $allow->value / 100)
                    : $allow->value;
                $totalAllowance += $amount;
                $lines[] = ['component_code' => $allow->code, 'label' => $allow->name, 'type' => 'earning', 'amount' => $amount];
            }

            foreach ($deductions as $deduct) {
                $amount = $deduct->calc_type === 'percent_of_basic'
                    ? ($basicSalary * $deduct->value / 100)
                    : $deduct->value;
                $totalDeduction += $amount;
                $lines[] = ['component_code' => $deduct->code, 'label' => $deduct->name, 'type' => 'deduction', 'amount' => $amount];
            }

            // Overtime
            $otMinutes = AttendanceRecord::where('employee_id', $employee->id)
                ->whereMonth('date', $run->period_month)
                ->whereYear('date', $run->period_year)
                ->where('overtime_approved', true)
                ->sum('overtime_minutes');
            $otHours = $otMinutes / 60;
            $hourlyRate = $basicSalary / (22 * 8);
            $otAmount = $otHours * $hourlyRate * 1.5;

            // Bonus & Commission
            $bonus = Bonus::where('employee_id', $employee->id)
                ->where('period_year', $run->period_year)
                ->where('period_month', $run->period_month)
                ->sum('amount');
            $commission = Commission::where('employee_id', $employee->id)
                ->where('period_year', $run->period_year)
                ->where('period_month', $run->period_month)
                ->sum('amount');

            // Attendance
            $presentDays = AttendanceRecord::where('employee_id', $employee->id)
                ->whereMonth('date', $run->period_month)
                ->whereYear('date', $run->period_year)
                ->whereIn('status', ['present', 'late'])
                ->count();
            $absentDays = AttendanceRecord::where('employee_id', $employee->id)
                ->whereMonth('date', $run->period_month)
                ->whereYear('date', $run->period_year)
                ->where('status', 'absent')
                ->count();
            $leaveDays = AttendanceRecord::where('employee_id', $employee->id)
                ->whereMonth('date', $run->period_month)
                ->whereYear('date', $run->period_year)
                ->where('status', 'on_leave')
                ->count();

            $grossPay = $basicSalary + $totalAllowance + $otAmount + $bonus + $commission;
            $netPay = $grossPay - $totalDeduction;

            $payslip = Payslip::create([
                'payroll_run_id' => $run->id,
                'employee_id' => $employee->id,
                'basic_salary' => $basicSalary,
                'total_allowances' => $totalAllowance,
                'total_deductions' => $totalDeduction,
                'overtime_hours' => $otHours,
                'overtime_amount' => $otAmount,
                'bonus' => $bonus,
                'commission' => $commission,
                'gross_pay' => $grossPay,
                'net_pay' => $netPay,
                'working_days' => 22,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDays,
                'status' => 'draft',
            ]);

            foreach ($lines as $i => $line) {
                PayslipLine::create(array_merge($line, [
                    'payslip_id' => $payslip->id,
                    'sort_order' => $i,
                ]));
            }

            $totalGross += $grossPay;
            $totalDeductions += $totalDeduction;
            $totalNet += $netPay;
        }

        $run->update([
            'status' => 'review',
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
            'employee_count' => $employees->count(),
        ]);

        return back()->with('success', 'Payroll processed. ' . $employees->count() . ' payslips generated.');
    }

    public function runsApprove(PayrollRun $run)
    {
        if ($run->status !== 'review') {
            return back()->with('error', 'Run must be in review state.');
        }
        $run->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return back()->with('success', 'Payroll approved.');
    }

    public function runsMarkPaid(PayrollRun $run)
    {
        if ($run->status !== 'approved') {
            return back()->with('error', 'Run must be approved first.');
        }
        $run->update(['status' => 'paid', 'paid_at' => now()]);
        $run->payslips()->update(['status' => 'final']);
        return back()->with('success', 'Payroll marked as paid. Payslips finalized.');
    }

    public function payslipsShow(Payslip $payslip)
    {
        $payslip->load(['employee.department', 'employee.position', 'lines', 'payrollRun']);
        return view('payroll.payslip', compact('payslip'));
    }

    public function bonusesIndex()
    {
        $bonuses = Bonus::with('employee')->orderBy('created_at', 'desc')->paginate(15);
        $employees = Employee::where('employment_status', 'active')->get();
        return view('payroll.bonuses', compact('bonuses', 'employees'));
    }

    public function bonusesStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_year' => 'required|integer',
            'period_month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        Bonus::create(array_merge($validated, ['created_by' => auth()->id()]));
        return back()->with('success', 'Bonus added.');
    }

    public function bonusesDestroy(Bonus $bonus)
    {
        $bonus->delete();
        return back()->with('success', 'Bonus deleted.');
    }

    public function commissionsIndex()
    {
        $commissions = Commission::with('employee')->orderBy('created_at', 'desc')->paginate(15);
        $employees = Employee::where('employment_status', 'active')->get();
        return view('payroll.commissions', compact('commissions', 'employees'));
    }

    public function commissionsStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_year' => 'required|integer',
            'period_month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0',
            'basis' => 'nullable|string',
        ]);
        Commission::create(array_merge($validated, ['created_by' => auth()->id()]));
        return back()->with('success', 'Commission added.');
    }

    public function commissionsDestroy(Commission $commission)
    {
        $commission->delete();
        return back()->with('success', 'Commission deleted.');
    }
}

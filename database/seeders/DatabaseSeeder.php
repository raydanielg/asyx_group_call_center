<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use App\Models\Team;
use App\Models\WorkingHourPolicy;
use App\Models\Holiday;
use App\Models\CompanyPolicy;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeContract;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeDocument;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\AttendanceRecord;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\SalaryComponent;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\Bonus;
use App\Models\Commission;
use App\Models\JobPosition;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\OnboardingChecklist;
use App\Models\OnboardingTask;
use App\Models\EmployeeOnboarding;
use App\Models\EmployeeOnboardingTask;
use App\Models\Kpi;
use App\Models\KpiTarget;
use App\Models\PerformanceEvaluation;
use App\Models\AgentDailyStat;
use App\Models\AuditLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (User::count() > 0) {
            $this->command->warn('Database already has data. Skipping seeder.');
            $this->command->info('To re-seed from scratch, run: php artisan migrate:fresh --seed');
            return;
        }

        $this->command->info('Starting database seeding...');
        $startTime = microtime(true);

        DB::transaction(function () {
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // USERS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating users...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@ayscallcenter.com'],
            ['name' => 'System Admin', 'first_name' => 'System', 'last_name' => 'Admin', 'phone' => '+255700000001', 'role' => 'admin', 'status' => 'active', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $hrManager = User::firstOrCreate(
            ['email' => 'hr@ayscallcenter.co.tz'],
            ['name' => 'HR Manager', 'first_name' => 'Jane', 'last_name' => 'Mwangi', 'phone' => '+255700000002', 'role' => 'admin', 'status' => 'active', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $qaLead = User::firstOrCreate(
            ['email' => 'qa@ayscallcenter.com'],
            ['name' => 'QA Lead', 'first_name' => 'Joseph', 'last_name' => 'Komba', 'phone' => '+255700000003', 'role' => 'admin', 'status' => 'active', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // BRANCHES
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $branches = [
            ['name' => 'Dar es Salaam HQ', 'code' => 'DSM-HQ', 'address' => 'Plot 123, Mikocheni', 'city' => 'Dar es Salaam', 'phone' => '+255222000001', 'timezone' => 'Africa/Dar_es_Salaam', 'is_active' => true],
            ['name' => 'Arusha Branch', 'code' => 'ARU-01', 'address' => 'Plot 45, Themi', 'city' => 'Arusha', 'phone' => '+255272000002', 'timezone' => 'Africa/Dar_es_Salaam', 'is_active' => true],
            ['name' => 'Mwanza Branch', 'code' => 'MWZ-01', 'address' => 'Plot 78, Capri Point', 'city' => 'Mwanza', 'phone' => '+255282000003', 'timezone' => 'Africa/Dar_es_Salaam', 'is_active' => true],
        ];
        $this->command->info('  → Creating branches...');
        foreach ($branches as $b) Branch::create($b);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // DEPARTMENTS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $departments = [
            ['branch_id' => 1, 'name' => 'Customer Service', 'code' => 'CS', 'is_active' => true],
            ['branch_id' => 1, 'name' => 'Sales', 'code' => 'SAL', 'is_active' => true],
            ['branch_id' => 1, 'name' => 'Technical Support', 'code' => 'TECH', 'is_active' => true],
            ['branch_id' => 1, 'name' => 'Human Resources', 'code' => 'HR', 'is_active' => true],
            ['branch_id' => 1, 'name' => 'Quality Assurance', 'code' => 'QA', 'is_active' => true],
            ['branch_id' => 2, 'name' => 'Customer Service', 'code' => 'CS-ARU', 'is_active' => true],
            ['branch_id' => 2, 'name' => 'Sales', 'code' => 'SAL-ARU', 'is_active' => true],
            ['branch_id' => 3, 'name' => 'Customer Service', 'code' => 'CS-MWZ', 'is_active' => true],
        ];
        $this->command->info('  → Creating departments...');
        foreach ($departments as $d) Department::create($d);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // POSITIONS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $positions = [
            ['department_id' => 1, 'title' => 'Call Center Agent', 'code' => 'CCA', 'level' => 'agent', 'min_salary' => 350000, 'max_salary' => 600000, 'is_active' => true],
            ['department_id' => 1, 'title' => 'Senior Call Center Agent', 'code' => 'SCCA', 'level' => 'senior_agent', 'min_salary' => 600000, 'max_salary' => 900000, 'is_active' => true],
            ['department_id' => 1, 'title' => 'Team Supervisor', 'code' => 'TS', 'level' => 'supervisor', 'min_salary' => 900000, 'max_salary' => 1500000, 'is_active' => true],
            ['department_id' => 1, 'title' => 'Call Center Manager', 'code' => 'CCM', 'level' => 'manager', 'min_salary' => 1500000, 'max_salary' => 2500000, 'is_active' => true],
            ['department_id' => 2, 'title' => 'Sales Agent', 'code' => 'SA', 'level' => 'agent', 'min_salary' => 400000, 'max_salary' => 700000, 'is_active' => true],
            ['department_id' => 2, 'title' => 'Sales Team Lead', 'code' => 'STL', 'level' => 'team_lead', 'min_salary' => 1000000, 'max_salary' => 1800000, 'is_active' => true],
            ['department_id' => 3, 'title' => 'Tech Support Agent', 'code' => 'TSA', 'level' => 'agent', 'min_salary' => 450000, 'max_salary' => 750000, 'is_active' => true],
            ['department_id' => 4, 'title' => 'HR Officer', 'code' => 'HRO', 'level' => 'supervisor', 'min_salary' => 1000000, 'max_salary' => 1800000, 'is_active' => true],
            ['department_id' => 5, 'title' => 'QA Evaluator', 'code' => 'QAE', 'level' => 'senior_agent', 'min_salary' => 700000, 'max_salary' => 1200000, 'is_active' => true],
            ['department_id' => 6, 'title' => 'Call Center Agent', 'code' => 'CCA-A', 'level' => 'agent', 'min_salary' => 350000, 'max_salary' => 600000, 'is_active' => true],
        ];
        $this->command->info('  → Creating positions...');
        foreach ($positions as $p) Position::create($p);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // TEAMS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $teams = [
            ['department_id' => 1, 'name' => 'Team Alpha', 'is_active' => true],
            ['department_id' => 1, 'name' => 'Team Bravo', 'is_active' => true],
            ['department_id' => 1, 'name' => 'Team Charlie', 'is_active' => true],
            ['department_id' => 2, 'name' => 'Sales Team 1', 'is_active' => true],
            ['department_id' => 2, 'name' => 'Sales Team 2', 'is_active' => true],
            ['department_id' => 3, 'name' => 'Tech Team A', 'is_active' => true],
            ['department_id' => 6, 'name' => 'Arusha Team 1', 'is_active' => true],
        ];
        $this->command->info('  → Creating teams...');
        foreach ($teams as $t) Team::create($t);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // WORKING HOUR POLICIES
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating working hour policies...');
        WorkingHourPolicy::create(['name' => 'Standard 8hr', 'hours_per_day' => 8, 'days_per_week' => 5, 'week_start' => 'mon', 'grace_minutes' => 10, 'overtime_after_minutes' => 480, 'is_default' => true]);
        WorkingHourPolicy::create(['name' => 'Shift 9hr (with 1hr break)', 'hours_per_day' => 8, 'days_per_week' => 6, 'week_start' => 'mon', 'grace_minutes' => 5, 'overtime_after_minutes' => 540, 'is_default' => false]);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // HOLIDAYS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $holidays = [
            ['name' => 'New Year', 'date' => now()->year . '-01-01', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Zanzibar Revolution Day', 'date' => now()->year . '-01-12', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Good Friday', 'date' => now()->year . '-03-29', 'is_recurring' => false, 'branch_id' => null],
            ['name' => 'Easter Monday', 'date' => now()->year . '-04-01', 'is_recurring' => false, 'branch_id' => null],
            ['name' => 'Union Day', 'date' => now()->year . '-04-26', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Labour Day', 'date' => now()->year . '-05-01', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Saba Saba Day', 'date' => now()->year . '-07-07', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Nane Nane Day', 'date' => now()->year . '-08-08', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Christmas Day', 'date' => now()->year . '-12-25', 'is_recurring' => true, 'branch_id' => null],
            ['name' => 'Boxing Day', 'date' => now()->year . '-12-26', 'is_recurring' => true, 'branch_id' => null],
        ];
        $this->command->info('  → Creating holidays...');
        foreach ($holidays as $h) Holiday::create($h);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // COMPANY POLICIES
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating company policies...');
        CompanyPolicy::create(['title' => 'Code of Conduct', 'category' => 'conduct', 'body' => 'All employees must adhere to the company code of conduct...', 'version' => 1, 'effective_from' => now()->startOfYear(), 'is_active' => true]);
        CompanyPolicy::create(['title' => 'Leave Policy', 'category' => 'leave', 'body' => 'Annual leave entitlement is 21 days per year...', 'version' => 2, 'effective_from' => now()->startOfYear(), 'is_active' => true]);
        CompanyPolicy::create(['title' => 'Remote Work Policy', 'category' => 'other', 'body' => 'Remote work is allowed for specific roles...', 'version' => 1, 'effective_from' => now()->startOfYear(), 'is_active' => true]);
        CompanyPolicy::create(['title' => 'Disciplinary Policy', 'category' => 'hr', 'body' => 'Disciplinary procedures follow a 3-step warning process...', 'version' => 2, 'effective_from' => now()->startOfYear(), 'is_active' => true]);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // SHIFTS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $shifts = [
            ['name' => 'Morning Shift', 'code' => 'MS', 'start_time' => '06:00', 'end_time' => '14:00', 'crosses_midnight' => false, 'break_minutes' => 60, 'color' => '#3B82F6', 'is_night_shift' => false, 'is_active' => true],
            ['name' => 'Afternoon Shift', 'code' => 'AS', 'start_time' => '14:00', 'end_time' => '22:00', 'crosses_midnight' => false, 'break_minutes' => 60, 'color' => '#F59E0B', 'is_night_shift' => false, 'is_active' => true],
            ['name' => 'Night Shift', 'code' => 'NS', 'start_time' => '22:00', 'end_time' => '06:00', 'crosses_midnight' => true, 'break_minutes' => 60, 'color' => '#8B5CF6', 'is_night_shift' => true, 'night_allowance' => 50000, 'is_active' => true],
            ['name' => 'Day Shift', 'code' => 'DS', 'start_time' => '08:00', 'end_time' => '17:00', 'crosses_midnight' => false, 'break_minutes' => 60, 'color' => '#10B981', 'is_night_shift' => false, 'is_active' => true],
        ];
        $this->command->info('  → Creating shifts...');
        foreach ($shifts as $s) Shift::create($s);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // LEAVE TYPES
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $leaveTypes = [
            ['name' => 'Annual Leave', 'code' => 'AL', 'days_per_year' => 21, 'is_paid' => true, 'carry_forward' => true, 'max_carry_forward' => 7, 'requires_attachment' => false, 'gender_restriction' => 'any', 'is_active' => true],
            ['name' => 'Sick Leave', 'code' => 'SL', 'days_per_year' => 10, 'is_paid' => true, 'carry_forward' => false, 'max_carry_forward' => 0, 'requires_attachment' => true, 'gender_restriction' => 'any', 'is_active' => true],
            ['name' => 'Maternity Leave', 'code' => 'ML', 'days_per_year' => 84, 'is_paid' => true, 'carry_forward' => false, 'max_carry_forward' => 0, 'requires_attachment' => false, 'gender_restriction' => 'female', 'is_active' => true],
            ['name' => 'Paternity Leave', 'code' => 'PL', 'days_per_year' => 3, 'is_paid' => true, 'carry_forward' => false, 'max_carry_forward' => 0, 'requires_attachment' => false, 'gender_restriction' => 'male', 'is_active' => true],
            ['name' => 'Compassionate Leave', 'code' => 'CL', 'days_per_year' => 3, 'is_paid' => true, 'carry_forward' => false, 'max_carry_forward' => 0, 'requires_attachment' => false, 'gender_restriction' => 'any', 'is_active' => true],
            ['name' => 'Unpaid Leave', 'code' => 'UL', 'days_per_year' => 30, 'is_paid' => false, 'carry_forward' => false, 'max_carry_forward' => 0, 'requires_attachment' => false, 'gender_restriction' => 'any', 'is_active' => true],
        ];
        $this->command->info('  → Creating leave types...');
        foreach ($leaveTypes as $lt) LeaveType::create($lt);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // SALARY COMPONENTS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $components = [
            ['name' => 'Housing Allowance', 'code' => 'HA', 'type' => 'allowance', 'calc_type' => 'percent_of_basic', 'value' => 15, 'is_taxable' => false, 'is_statutory' => false, 'is_active' => true],
            ['name' => 'Transport Allowance', 'code' => 'TA', 'type' => 'allowance', 'calc_type' => 'fixed', 'value' => 50000, 'is_taxable' => false, 'is_statutory' => false, 'is_active' => true],
            ['name' => 'Meal Allowance', 'code' => 'MA', 'type' => 'allowance', 'calc_type' => 'fixed', 'value' => 30000, 'is_taxable' => false, 'is_statutory' => false, 'is_active' => true],
            ['name' => 'Night Shift Allowance', 'code' => 'NSA', 'type' => 'allowance', 'calc_type' => 'fixed', 'value' => 50000, 'is_taxable' => true, 'is_statutory' => false, 'is_active' => true],
            ['name' => 'PAYE (Income Tax)', 'code' => 'PAYE', 'type' => 'deduction', 'calc_type' => 'percent_of_basic', 'value' => 15, 'is_taxable' => false, 'is_statutory' => true, 'is_active' => true],
            ['name' => 'NSSF (Pension)', 'code' => 'NSSF', 'type' => 'deduction', 'calc_type' => 'percent_of_basic', 'value' => 10, 'is_taxable' => false, 'is_statutory' => true, 'is_active' => true],
            ['name' => 'SHIF (Health Insurance)', 'code' => 'SHIF', 'type' => 'deduction', 'calc_type' => 'percent_of_basic', 'value' => 5, 'is_taxable' => false, 'is_statutory' => true, 'is_active' => true],
            ['name' => 'SDL (Skills Development Levy)', 'code' => 'SDL', 'type' => 'deduction', 'calc_type' => 'percent_of_basic', 'value' => 2, 'is_taxable' => false, 'is_statutory' => true, 'is_active' => true],
        ];
        $this->command->info('  → Creating salary components...');
        foreach ($components as $c) SalaryComponent::create($c);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // KPIs
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $kpis = [
            ['name' => 'Total Calls Handled', 'code' => 'total_calls', 'unit' => 'count', 'direction' => 'higher_better', 'weight' => 20, 'applies_to' => 'agent', 'is_active' => true],
            ['name' => 'Answer Rate', 'code' => 'answered_calls', 'unit' => 'count', 'direction' => 'higher_better', 'weight' => 15, 'applies_to' => 'agent', 'is_active' => true],
            ['name' => 'Average Handle Time', 'code' => 'aht', 'unit' => 'seconds', 'direction' => 'lower_better', 'weight' => 20, 'applies_to' => 'agent', 'is_active' => true],
            ['name' => 'CSAT Score', 'code' => 'csat', 'unit' => 'score', 'direction' => 'higher_better', 'weight' => 25, 'applies_to' => 'agent', 'is_active' => true],
            ['name' => 'Conversions', 'code' => 'conversions', 'unit' => 'count', 'direction' => 'higher_better', 'weight' => 20, 'applies_to' => 'agent', 'is_active' => true],
        ];
        $this->command->info('  → Creating KPIs...');
        foreach ($kpis as $k) Kpi::create($k);

        // KPI Targets (company-wide for current year)
        $kpiTargets = [
            ['kpi_id' => 1, 'scope' => 'company', 'period_year' => now()->year, 'target_value' => 2000],
            ['kpi_id' => 2, 'scope' => 'company', 'period_year' => now()->year, 'target_value' => 1800],
            ['kpi_id' => 3, 'scope' => 'company', 'period_year' => now()->year, 'target_value' => 300],
            ['kpi_id' => 4, 'scope' => 'company', 'period_year' => now()->year, 'target_value' => 4],
            ['kpi_id' => 5, 'scope' => 'company', 'period_year' => now()->year, 'target_value' => 150],
        ];
        foreach ($kpiTargets as $kt) KpiTarget::create($kt);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ONBOARDING CHECKLIST
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating onboarding checklist...');
        $checklist = OnboardingChecklist::create([
            'name' => 'Standard New Hire Checklist',
            'is_default' => true,
        ]);

        $checklistTasks = [
            'Collect ID & documents',
            'Sign employment contract',
            'Set up system accounts',
            'Issue staff ID card',
            'Company orientation session',
            'Product training module 1',
            'Shadow a senior agent',
            'First call assessment',
        ];
        foreach ($checklistTasks as $i => $task) {
            OnboardingTask::create([
                'checklist_id' => $checklist->id,
                'title' => $task,
                'sort_order' => $i + 1,
            ]);
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // EMPLOYEES
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $firstNames = ['Asha', 'John', 'Mary', 'David', 'Grace', 'Peter', 'Sarah', 'Michael', 'Rose', 'James', 'Lucy', 'Daniel', 'Nancy', 'Eric', 'Diana', 'Brian', 'Faith', 'Kevin', 'Esther', 'Samuel', 'Ruth', 'Joseph', 'Anna', 'Thomas', 'Lillian', 'Henry', 'Martha', 'Paul', 'Joyce', 'Albert'];
        $lastNames = ['Mwangi', 'Komba', 'Joseph', 'Mushi', 'Massawe', 'Kessy', 'Lyimo', 'Nyerere', 'Mrema', 'Shirima', 'Mwita', 'Njau', 'Kariuki', 'Odhiambo', 'Chuma', 'Mahenge', 'Mlay', 'Sanga', 'Kabwe', 'Mbowe'];
        $genders = ['male', 'female'];
        $statuses = ['active', 'active', 'active', 'active', 'active', 'active', 'active', 'active', 'probation', 'on_leave'];
        $deptIds = [1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 5, 6, 6, 6, 1, 2, 3, 1];
        $teamIds = [1, 1, 2, 2, 3, 4, 4, 5, 6, 6, 6, null, null, 7, 7, 7, 1, 4, 6, 2];
        $posIds = [1, 1, 1, 2, 3, 5, 5, 6, 7, 7, 6, 8, 9, 10, 10, 10, 1, 5, 7, 2];
        $salaries = [450000, 450000, 450000, 650000, 950000, 480000, 480000, 1100000, 500000, 500000, 1100000, 1200000, 800000, 420000, 420000, 420000, 450000, 480000, 500000, 650000];

        $this->command->info('  → Creating 30 employees with salaries, contracts, bank accounts...');
        $employees = [];
        for ($i = 0; $i < 30; $i++) {
            $fn = $firstNames[$i];
            $ln = $lastNames[$i % count($lastNames)];
            $hireDate = Carbon::now()->subDays(rand(30, 900));
            $emp = Employee::create([
                'employee_code' => 'EMP-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => $fn,
                'last_name' => $ln,
                'gender' => $genders[$i % 2],
                'date_of_birth' => Carbon::now()->subYears(rand(22, 45))->subDays(rand(0, 365)),
                'marital_status' => ['single', 'married', 'married', 'single'][rand(0, 3)],
                'national_id' => 'ID-' . rand(10000000, 99999999),
                'email' => strtolower($fn) . '.' . strtolower($ln) . $i . '@ayscallcenter.com',
                'phone' => '+2557' . rand(10, 89) . rand(100000, 999999),
                'address' => 'Plot ' . rand(1, 500) . ', ' . ['Mikocheni', 'Kijitonyama', 'Sinza', 'Mabibo', 'Tabata'][rand(0, 4)],
                'city' => ['Dar es Salaam', 'Arusha', 'Mwanza'][rand(0, 2)],
                'country' => 'Tanzania',
                'branch_id' => $i < 20 ? 1 : ($i < 26 ? 2 : 3),
                'department_id' => $deptIds[$i % count($deptIds)],
                'position_id' => $posIds[$i % count($posIds)],
                'team_id' => $teamIds[$i % count($teamIds)],
                'employment_type' => ['full_time', 'full_time', 'full_time', 'contract'][rand(0, 3)],
                'hire_date' => $hireDate,
                'probation_end_date' => $hireDate->copy()->addMonths(3),
                'employment_status' => $statuses[$i % count($statuses)],
                'reports_to' => $i >= 4 && $i < 20 ? 5 : null,
                'created_by' => $admin->id,
            ]);
            $employees[] = $emp;

            // Salary record
            EmployeeSalary::create([
                'employee_id' => $emp->id,
                'effective_from' => $hireDate,
                'base_salary' => $salaries[$i % count($salaries)],
                'pay_frequency' => 'monthly',
                'created_by' => $admin->id,
            ]);

            // Contract
            EmployeeContract::create([
                'employee_id' => $emp->id,
                'contract_type' => $i % 4 === 3 ? 'fixed_term' : 'permanent',
                'start_date' => $hireDate,
                'end_date' => $i % 4 === 3 ? $hireDate->copy()->addYear() : null,
                'base_salary' => $salaries[$i % count($salaries)],
                'status' => 'active',
            ]);

            // Bank account
            EmployeeBankAccount::create([
                'employee_id' => $emp->id,
                'bank_name' => ['CRDB Bank', 'NMB Bank', 'NBC', 'Stanbic Bank'][rand(0, 3)],
                'account_name' => $fn . ' ' . $ln,
                'account_number' => '01' . rand(10000000, 99999999),
                'is_primary' => true,
            ]);

            // Emergency contact
            EmployeeEmergencyContact::create([
                'employee_id' => $emp->id,
                'name' => ['Grace', 'Peter', 'Mary', 'John'][$i % 4] . ' ' . $ln,
                'relationship' => ['Spouse', 'Parent', 'Sibling', 'Parent'][$i % 4],
                'phone' => '+2557' . rand(10, 89) . rand(100000, 999999),
            ]);

            // Document
            EmployeeDocument::create([
                'employee_id' => $emp->id,
                'category' => 'contract',
                'title' => 'Employment Contract',
                'uploaded_by' => $admin->id,
                'expires_at' => Carbon::now()->addDays(rand(30, 365)),
            ]);

            // Leave balances
            foreach ([1, 2] as $ltId) {
                LeaveBalance::create([
                    'employee_id' => $emp->id,
                    'leave_type_id' => $ltId,
                    'year' => now()->year,
                    'entitled' => $ltId == 1 ? 21 : 10,
                    'carried_over' => rand(0, 5),
                    'used' => rand(0, 10),
                ]);
            }
        }

        $this->command->info('  → Setting department managers & team leads...');
        // Set department managers & team leads
        Department::where('id', 1)->update(['manager_employee_id' => 5]);
        Department::where('id', 2)->update(['manager_employee_id' => 8]);
        Department::where('id', 3)->update(['manager_employee_id' => 11]);
        Team::where('id', 1)->update(['team_lead_employee_id' => 4]);
        Team::where('id', 2)->update(['team_lead_employee_id' => 4]);
        Team::where('id', 4)->update(['team_lead_employee_id' => 8]);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // SHIFT ASSIGNMENTS (last 7 days)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating shift assignments (7 days)...');
        $activeEmps = Employee::where('employment_status', 'active')->get();
        for ($d = 0; $d < 7; $d++) {
            $date = Carbon::now()->subDays($d)->toDateString();
            foreach ($activeEmps as $idx => $emp) {
                ShiftAssignment::create([
                    'employee_id' => $emp->id,
                    'shift_id' => ($idx % 3) + 1,
                    'date' => $date,
                    'assigned_by' => $admin->id,
                ]);
            }
        }

        $this->command->info('  → Creating attendance records (7 days)...');
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ATTENDANCE RECORDS (last 7 days)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        for ($d = 0; $d < 7; $d++) {
            $date = Carbon::now()->subDays($d);
            if (in_array($date->format('N'), [6, 7])) continue; // skip weekends
            foreach ($activeEmps as $emp) {
                $status = ['present', 'present', 'present', 'present', 'late', 'absent', 'on_leave'][rand(0, 6)];
                $checkIn = null;
                $checkOut = null;
                $lateMin = 0;
                $workedMin = 0;
                if (in_array($status, ['present', 'late'])) {
                    $checkIn = $date->copy()->setTime(8, $status === 'late' ? rand(11, 30) : rand(0, 9), 0);
                    $checkOut = $date->copy()->setTime(17, rand(0, 30), 0);
                    $workedMin = 480 + rand(-20, 30);
                    $lateMin = $status === 'late' ? rand(11, 30) : 0;
                }
                AttendanceRecord::create([
                    'employee_id' => $emp->id,
                    'date' => $date->toDateString(),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'late_minutes' => $lateMin,
                    'worked_minutes' => $workedMin,
                    'overtime_minutes' => rand(0, 1) ? rand(15, 90) : 0,
                    'overtime_approved' => rand(0, 1) ? true : false,
                    'source' => 'manual',
                    'recorded_by' => $admin->id,
                ]);
            }
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // LEAVE REQUESTS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $leaveRequests = [
            ['employee_id' => 3, 'leave_type_id' => 1, 'start_date' => now()->addDays(5), 'end_date' => now()->addDays(8), 'days' => 4, 'reason' => 'Family vacation', 'status' => 'pending', 'recorded_by' => $admin->id],
            ['employee_id' => 7, 'leave_type_id' => 2, 'start_date' => now()->subDays(3), 'end_date' => now()->subDays(2), 'days' => 2, 'reason' => 'Flu', 'status' => 'approved', 'decided_by' => $admin->id, 'decided_at' => now()->subDays(4), 'recorded_by' => $admin->id],
            ['employee_id' => 12, 'leave_type_id' => 1, 'start_date' => now()->subDays(10), 'end_date' => now()->subDays(7), 'days' => 4, 'reason' => 'Personal matters', 'status' => 'approved', 'decided_by' => $admin->id, 'decided_at' => now()->subDays(12), 'recorded_by' => $admin->id],
            ['employee_id' => 15, 'leave_type_id' => 3, 'start_date' => now()->subDays(30), 'end_date' => now()->addDays(53), 'days' => 84, 'reason' => 'Maternity leave', 'status' => 'approved', 'decided_by' => $admin->id, 'decided_at' => now()->subDays(35), 'recorded_by' => $admin->id],
            ['employee_id' => 20, 'leave_type_id' => 1, 'start_date' => now()->addDays(14), 'end_date' => now()->addDays(20), 'days' => 7, 'reason' => 'Annual leave', 'status' => 'pending', 'recorded_by' => $admin->id],
            ['employee_id' => 9, 'leave_type_id' => 6, 'start_date' => now()->subDays(5), 'end_date' => now()->subDays(3), 'days' => 3, 'reason' => 'Personal emergency', 'status' => 'rejected', 'decided_by' => $admin->id, 'decided_at' => now()->subDays(6), 'decision_note' => 'Insufficient notice', 'recorded_by' => $admin->id],
        ];
        $this->command->info('  → Creating leave requests...');
        foreach ($leaveRequests as $lr) LeaveRequest::create($lr);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // AGENT DAILY STATS (last 30 days for first 15 agents)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating agent daily stats (30 days × 15 agents)...');
        $agentEmps = Employee::where('employment_status', 'active')->take(15)->get();
        for ($d = 0; $d < 30; $d++) {
            $date = Carbon::now()->subDays($d);
            if (in_array($date->format('N'), [6, 7])) continue;
            foreach ($agentEmps as $emp) {
                $totalCalls = rand(40, 120);
                $answeredCalls = (int)($totalCalls * (0.7 + rand(0, 20) / 100));
                $missedCalls = $totalCalls - $answeredCalls;
                $outboundCalls = rand(5, 30);
                $talkTime = $answeredCalls * rand(120, 300);
                $holdTime = $answeredCalls * rand(10, 60);
                $wrapTime = $answeredCalls * rand(20, 60);
                $aht = $answeredCalls > 0 ? (int)(($talkTime + $holdTime + $wrapTime) / $answeredCalls) : 0;
                AgentDailyStat::create([
                    'employee_id' => $emp->id,
                    'date' => $date->toDateString(),
                    'total_calls' => $totalCalls,
                    'answered_calls' => $answeredCalls,
                    'missed_calls' => $missedCalls,
                    'outbound_calls' => $outboundCalls,
                    'talk_time_seconds' => $talkTime,
                    'hold_time_seconds' => $holdTime,
                    'wrap_time_seconds' => $wrapTime,
                    'aht_seconds' => $aht,
                    'conversions' => rand(0, 15),
                    'csat_score' => round(3.5 + rand(0, 15) / 10, 2),
                    'source' => 'manual',
                ]);
            }
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // JOB POSITIONS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $jobs = [
            ['title' => 'Call Center Agent - Dar es Salaam', 'department_id' => 1, 'position_id' => 1, 'description' => 'Handle inbound and outbound customer calls', 'requirements' => 'Excellent communication, basic computer skills', 'openings' => 5, 'employment_type' => 'full_time', 'salary_range_min' => 350000, 'salary_range_max' => 600000, 'status' => 'open', 'opened_at' => now()->subDays(10), 'created_by' => $admin->id],
            ['title' => 'Senior Sales Agent', 'department_id' => 2, 'position_id' => 6, 'description' => 'Drive sales through outbound calls', 'requirements' => '2+ years sales experience, proven track record', 'openings' => 2, 'employment_type' => 'full_time', 'salary_range_min' => 600000, 'salary_range_max' => 900000, 'status' => 'open', 'opened_at' => now()->subDays(5), 'created_by' => $admin->id],
            ['title' => 'Tech Support Specialist', 'department_id' => 3, 'position_id' => 7, 'description' => 'Provide technical support to customers', 'requirements' => 'IT background, troubleshooting skills', 'openings' => 3, 'employment_type' => 'full_time', 'salary_range_min' => 450000, 'salary_range_max' => 750000, 'status' => 'open', 'opened_at' => now()->subDays(3), 'created_by' => $admin->id],
            ['title' => 'QA Evaluator', 'department_id' => 5, 'position_id' => 9, 'description' => 'Evaluate call quality and provide feedback', 'requirements' => 'Call center experience, attention to detail', 'openings' => 1, 'employment_type' => 'full_time', 'salary_range_min' => 700000, 'salary_range_max' => 1200000, 'status' => 'on_hold', 'opened_at' => now()->subDays(20), 'created_by' => $admin->id],
            ['title' => 'Call Center Agent - Arusha', 'department_id' => 6, 'position_id' => 10, 'description' => 'Handle customer calls for Arusha branch', 'requirements' => 'Good communication, Swahili and English', 'openings' => 3, 'employment_type' => 'full_time', 'salary_range_min' => 350000, 'salary_range_max' => 600000, 'status' => 'open', 'opened_at' => now()->subDays(7), 'created_by' => $admin->id],
        ];
        $this->command->info('  → Creating job positions...');
        foreach ($jobs as $j) JobPosition::create($j);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // APPLICANTS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $applicantNames = [
            ['Frank', 'Mwakalukwa'], ['Stella', 'Kagaruki'], ['Oscar', 'Mwakyusa'], ['Beatrice', 'Mlay'], ['Hassan', 'Ali'],
            ['Tatu', 'Hassan'], ['Eric', 'Shirima'], ['Vivian', 'Mushi'], ['Godfrey', 'Nyerere'], ['Amina', 'Said'],
            ['Patrick', 'Mbowe'], ['Zainab', 'Khalfan'], ['Yusuf', 'Mgeni'], ['Catherine', 'Joseph'], ['Idd', 'Komba'],
        ];
        $stages = ['applied', 'applied', 'screening', 'screening', 'interview', 'interview', 'offer', 'hired', 'rejected', 'applied'];
        $sources = ['referral', 'online', 'walk_in', 'agency', 'online'];
        $this->command->info('  → Creating applicants...');
        for ($i = 0; $i < 15; $i++) {
            Applicant::create([
                'job_position_id' => ($i % 5) + 1,
                'first_name' => $applicantNames[$i][0],
                'last_name' => $applicantNames[$i][1],
                'email' => strtolower($applicantNames[$i][0]) . '.' . strtolower($applicantNames[$i][1]) . '@gmail.com',
                'phone' => '+2557' . rand(10, 89) . rand(100000, 999999),
                'source' => $sources[$i % 5],
                'stage' => $stages[$i % count($stages)],
                'rating' => rand(3, 5),
                'notes' => $i % 3 === 0 ? 'Strong candidate with relevant experience' : null,
                'created_by' => $admin->id,
            ]);
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // INTERVIEWS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating interviews...');
        Interview::create(['applicant_id' => 5, 'round' => 1, 'type' => 'phone', 'scheduled_at' => now()->addDays(2), 'duration_minutes' => 30, 'interviewer_user_id' => $hrManager->id, 'location' => 'Phone']);
        Interview::create(['applicant_id' => 6, 'round' => 1, 'type' => 'onsite', 'scheduled_at' => now()->addDays(3), 'duration_minutes' => 60, 'interviewer_user_id' => $hrManager->id, 'location' => 'DSM HQ - Room A']);
        Interview::create(['applicant_id' => 7, 'round' => 1, 'type' => 'video', 'scheduled_at' => now()->subDays(2), 'duration_minutes' => 45, 'interviewer_user_id' => $qaLead->id, 'status' => 'completed', 'score' => 78, 'feedback' => 'Good communication, needs more product knowledge']);
        Interview::create(['applicant_id' => 8, 'round' => 1, 'type' => 'onsite', 'scheduled_at' => now()->subDays(5), 'duration_minutes' => 60, 'interviewer_user_id' => $hrManager->id, 'status' => 'completed', 'score' => 85, 'feedback' => 'Excellent candidate, strong experience']);
        Interview::create(['applicant_id' => 8, 'round' => 2, 'type' => 'assessment', 'scheduled_at' => now()->subDays(3), 'duration_minutes' => 90, 'interviewer_user_id' => $qaLead->id, 'status' => 'completed', 'score' => 88, 'feedback' => 'Passed assessment with high marks']);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ONBOARDING (for hired applicant -> employee)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating onboarding data...');
        $onboarding = EmployeeOnboarding::create([
            'employee_id' => 30,
            'checklist_id' => $checklist->id,
            'status' => 'in_progress',
            'started_at' => now()->subDays(5),
        ]);
        foreach ($checklistTasks as $i => $task) {
            EmployeeOnboardingTask::create([
                'onboarding_id' => $onboarding->id,
                'task_title' => $task,
                'is_done' => $i < 3,
                'done_at' => $i < 3 ? now()->subDays(5 - $i) : null,
                'done_by' => $i < 3 ? $admin->id : null,
            ]);
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // PAYROLL RUN + PAYSLIPS (last month)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating payroll run & payslips...');
        $lastMonth = now()->subMonth();
        $run = PayrollRun::create([
            'period_year' => $lastMonth->year,
            'period_month' => $lastMonth->month,
            'status' => 'approved',
            'processed_by' => $admin->id,
            'approved_by' => $admin->id,
            'employee_count' => $activeEmps->count(),
        ]);

        $totalGross = 0;
        $totalDed = 0;
        $totalNet = 0;
        $allowComponents = SalaryComponent::where('type', 'allowance')->where('is_active', true)->get();
        $dedComponents = SalaryComponent::where('type', 'deduction')->where('is_active', true)->get();

        foreach ($activeEmps as $emp) {
            $salary = $emp->currentSalary();
            $basic = $salary ? $salary->base_salary : 450000;
            $totalAllow = 0;
            $totalDeduct = 0;
            $lines = [];

            foreach ($allowComponents as $a) {
                $amt = $a->calc_type === 'percent_of_basic' ? ($basic * $a->value / 100) : $a->value;
                $totalAllow += $amt;
                $lines[] = ['component_code' => $a->code, 'label' => $a->name, 'type' => 'earning', 'amount' => $amt];
            }
            foreach ($dedComponents as $d) {
                $amt = $d->calc_type === 'percent_of_basic' ? ($basic * $d->value / 100) : $d->value;
                $totalDeduct += $amt;
                $lines[] = ['component_code' => $d->code, 'label' => $d->name, 'type' => 'deduction', 'amount' => $amt];
            }

            $gross = $basic + $totalAllow;
            $net = $gross - $totalDeduct;
            $totalGross += $gross;
            $totalDed += $totalDeduct;
            $totalNet += $net;

            $payslip = Payslip::create([
                'payroll_run_id' => $run->id,
                'employee_id' => $emp->id,
                'basic_salary' => $basic,
                'total_allowances' => $totalAllow,
                'total_deductions' => $totalDeduct,
                'overtime_hours' => round(rand(0, 20) + rand(0, 99) / 100, 2),
                'overtime_amount' => rand(0, 50000),
                'bonus' => 0,
                'commission' => 0,
                'gross_pay' => $gross,
                'net_pay' => $net,
                'working_days' => 22,
                'present_days' => rand(18, 22),
                'absent_days' => rand(0, 3),
                'leave_days' => rand(0, 2),
                'status' => 'final',
            ]);

            foreach ($lines as $i => $line) {
                PayslipLine::create(array_merge($line, [
                    'payslip_id' => $payslip->id,
                    'sort_order' => $i,
                ]));
            }
        }

        $run->update([
            'total_gross' => $totalGross,
            'total_deductions' => $totalDed,
            'total_net' => $totalNet,
        ]);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // BONUSES & COMMISSIONS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating bonuses & commissions...');
        Bonus::create(['employee_id' => 4, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 100000, 'reason' => 'Top performer', 'created_by' => $admin->id]);
        Bonus::create(['employee_id' => 8, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 75000, 'reason' => 'Best team lead', 'created_by' => $admin->id]);
        Bonus::create(['employee_id' => 11, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 50000, 'reason' => 'Perfect attendance', 'created_by' => $admin->id]);

        Commission::create(['employee_id' => 6, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 120000, 'basis' => 'Sales conversion', 'created_by' => $admin->id]);
        Commission::create(['employee_id' => 7, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 85000, 'basis' => 'Sales conversion', 'created_by' => $admin->id]);
        Commission::create(['employee_id' => 8, 'period_year' => $lastMonth->year, 'period_month' => $lastMonth->month, 'amount' => 150000, 'basis' => 'Team sales target', 'created_by' => $admin->id]);

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // PERFORMANCE EVALUATIONS (last month)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating performance evaluations...');
        $allKpis = Kpi::where('is_active', true)->get();
        foreach ($activeEmps->take(20) as $emp) {
            $kpiScores = [];
            $weightedTotal = 0;
            $weightSum = 0;
            foreach ($allKpis as $kpi) {
                $score = rand(55, 98);
                $kpiScores[] = ['kpi_id' => $kpi->id, 'kpi_name' => $kpi->name, 'actual' => rand(100, 2000), 'target' => 1000, 'score' => $score];
                $weightedTotal += $score * $kpi->weight;
                $weightSum += $kpi->weight;
            }
            $weighted = $weightSum > 0 ? round($weightedTotal / $weightSum, 2) : 0;
            $grade = $weighted >= 90 ? 'A' : ($weighted >= 80 ? 'B' : ($weighted >= 70 ? 'C' : ($weighted >= 60 ? 'D' : 'E')));
            PerformanceEvaluation::create([
                'employee_id' => $emp->id,
                'period_year' => $lastMonth->year,
                'period_month' => $lastMonth->month,
                'kpi_scores' => $kpiScores,
                'weighted_score' => $weighted,
                'grade' => $grade,
                'evaluated_by' => $admin->id,
                'status' => 'finalized',
            ]);
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // AUD LOGS
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->command->info('  → Creating audit logs...');
        $auditLogs = [
            ['user_id' => $admin->id, 'action' => 'create', 'auditable_type' => 'App\\Models\\Employee', 'auditable_id' => 1, 'occurred_at' => now()->subDays(30), 'new_values' => ['first_name' => 'Asha', 'last_name' => 'Mwangi'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $admin->id, 'action' => 'create', 'auditable_type' => 'App\\Models\\Employee', 'auditable_id' => 2, 'occurred_at' => now()->subDays(29), 'new_values' => ['first_name' => 'John', 'last_name' => 'Komba'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $hrManager->id, 'action' => 'update', 'auditable_type' => 'App\\Models\\Employee', 'auditable_id' => 1, 'occurred_at' => now()->subDays(15), 'old_values' => ['department_id' => 2], 'new_values' => ['department_id' => 1], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $admin->id, 'action' => 'create', 'auditable_type' => 'App\\Models\\PayrollRun', 'auditable_id' => 1, 'occurred_at' => now()->subDays(10), 'new_values' => ['period' => 'last month'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $admin->id, 'action' => 'approve', 'auditable_type' => 'App\\Models\\PayrollRun', 'auditable_id' => 1, 'occurred_at' => now()->subDays(8), 'old_values' => ['status' => 'processed'], 'new_values' => ['status' => 'approved'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $hrManager->id, 'action' => 'update', 'auditable_type' => 'App\\Models\\LeaveRequest', 'auditable_id' => 2, 'occurred_at' => now()->subDays(4), 'old_values' => ['status' => 'pending'], 'new_values' => ['status' => 'approved'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
            ['user_id' => $admin->id, 'action' => 'delete', 'auditable_type' => 'App\\Models\\Employee', 'auditable_id' => 99, 'occurred_at' => now()->subDays(2), 'old_values' => ['first_name' => 'Test'], 'ip_address' => '127.0.0.1', 'user_agent' => 'Mozilla/5.0'],
        ];
        foreach ($auditLogs as $al) AuditLog::create($al);
        }); // end DB::transaction

        $elapsed = round(microtime(true) - $startTime, 2);
        $this->command->info("✓ Database seeded successfully in {$elapsed}s");
        $this->command->info('  Login: admin@ayscallcenter.com / password');
    }
}

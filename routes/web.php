<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Employees
    Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::post('/employees/{employee}/terminate', [App\Http\Controllers\EmployeeController::class, 'terminate'])->name('employees.terminate');
    Route::post('/employees/{employee}/documents', [App\Http\Controllers\EmployeeController::class, 'storeDocument'])->name('employees.documents.store');
    Route::post('/employees/{employee}/contracts', [App\Http\Controllers\EmployeeController::class, 'storeContract'])->name('employees.contracts.store');
    Route::post('/employees/{employee}/salaries', [App\Http\Controllers\EmployeeController::class, 'storeSalary'])->name('employees.salaries.store');
    Route::post('/employees/{employee}/bank-accounts', [App\Http\Controllers\EmployeeController::class, 'storeBankAccount'])->name('employees.bank-accounts.store');
    Route::post('/employees/{employee}/emergency-contacts', [App\Http\Controllers\EmployeeController::class, 'storeEmergencyContact'])->name('employees.emergency-contacts.store');
    Route::get('/employees-documents/expiry', [App\Http\Controllers\EmployeeController::class, 'documentsExpiry'])->name('employees.documents-expiry');

    // Organization (admin/owner only)
    Route::prefix('organization')->middleware('role:admin,owner')->group(function () {
        Route::get('/branches', [App\Http\Controllers\OrganizationController::class, 'branchesIndex'])->name('organization.branches');
        Route::post('/branches', [App\Http\Controllers\OrganizationController::class, 'branchesStore'])->name('organization.branches.store');
        Route::put('/branches/{branch}', [App\Http\Controllers\OrganizationController::class, 'branchesUpdate'])->name('organization.branches.update');
        Route::delete('/branches/{branch}', [App\Http\Controllers\OrganizationController::class, 'branchesDestroy'])->name('organization.branches.destroy');

        Route::get('/departments', [App\Http\Controllers\OrganizationController::class, 'departmentsIndex'])->name('organization.departments');
        Route::post('/departments', [App\Http\Controllers\OrganizationController::class, 'departmentsStore'])->name('organization.departments.store');
        Route::put('/departments/{department}', [App\Http\Controllers\OrganizationController::class, 'departmentsUpdate'])->name('organization.departments.update');
        Route::delete('/departments/{department}', [App\Http\Controllers\OrganizationController::class, 'departmentsDestroy'])->name('organization.departments.destroy');

        Route::get('/positions', [App\Http\Controllers\OrganizationController::class, 'positionsIndex'])->name('organization.positions');
        Route::post('/positions', [App\Http\Controllers\OrganizationController::class, 'positionsStore'])->name('organization.positions.store');
        Route::put('/positions/{position}', [App\Http\Controllers\OrganizationController::class, 'positionsUpdate'])->name('organization.positions.update');
        Route::delete('/positions/{position}', [App\Http\Controllers\OrganizationController::class, 'positionsDestroy'])->name('organization.positions.destroy');

        Route::get('/teams', [App\Http\Controllers\OrganizationController::class, 'teamsIndex'])->name('organization.teams');
        Route::post('/teams', [App\Http\Controllers\OrganizationController::class, 'teamsStore'])->name('organization.teams.store');
        Route::put('/teams/{team}', [App\Http\Controllers\OrganizationController::class, 'teamsUpdate'])->name('organization.teams.update');
        Route::delete('/teams/{team}', [App\Http\Controllers\OrganizationController::class, 'teamsDestroy'])->name('organization.teams.destroy');

        Route::get('/working-hours', [App\Http\Controllers\OrganizationController::class, 'workingHoursIndex'])->name('organization.working-hours');
        Route::post('/working-hours', [App\Http\Controllers\OrganizationController::class, 'workingHoursStore'])->name('organization.working-hours.store');
        Route::put('/working-hours/{policy}', [App\Http\Controllers\OrganizationController::class, 'workingHoursUpdate'])->name('organization.working-hours.update');
        Route::delete('/working-hours/{policy}', [App\Http\Controllers\OrganizationController::class, 'workingHoursDestroy'])->name('organization.working-hours.destroy');

        Route::get('/holidays', [App\Http\Controllers\OrganizationController::class, 'holidaysIndex'])->name('organization.holidays');
        Route::post('/holidays', [App\Http\Controllers\OrganizationController::class, 'holidaysStore'])->name('organization.holidays.store');
        Route::delete('/holidays/{holiday}', [App\Http\Controllers\OrganizationController::class, 'holidaysDestroy'])->name('organization.holidays.destroy');

        Route::get('/policies', [App\Http\Controllers\OrganizationController::class, 'policiesIndex'])->name('organization.policies');
        Route::post('/policies', [App\Http\Controllers\OrganizationController::class, 'policiesStore'])->name('organization.policies.store');
        Route::put('/policies/{policy}', [App\Http\Controllers\OrganizationController::class, 'policiesUpdate'])->name('organization.policies.update');
        Route::delete('/policies/{policy}', [App\Http\Controllers\OrganizationController::class, 'policiesDestroy'])->name('organization.policies.destroy');
    });

    // Attendance
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/bulk', [App\Http\Controllers\AttendanceController::class, 'bulkMark'])->name('attendance.bulk');
    Route::get('/attendance/missing', [App\Http\Controllers\AttendanceController::class, 'missing'])->name('attendance.missing');
    Route::get('/attendance/corrections', [App\Http\Controllers\AttendanceController::class, 'corrections'])->name('attendance.corrections');
    Route::post('/attendance/{record}/approve-overtime', [App\Http\Controllers\AttendanceController::class, 'approveOvertime'])->name('attendance.approve-overtime');
    Route::get('/attendance/summary', [App\Http\Controllers\AttendanceController::class, 'summary'])->name('attendance.summary');

    // Shifts
    Route::get('/shifts', [App\Http\Controllers\ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts', [App\Http\Controllers\ShiftController::class, 'store'])->name('shifts.store');
    Route::put('/shifts/{shift}', [App\Http\Controllers\ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('/shifts/{shift}', [App\Http\Controllers\ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('/shifts/planner', [App\Http\Controllers\ShiftController::class, 'planner'])->name('shifts.planner');
    Route::post('/shifts/assign', [App\Http\Controllers\ShiftController::class, 'assign'])->name('shifts.assign');
    Route::post('/shifts/unassign', [App\Http\Controllers\ShiftController::class, 'unassign'])->name('shifts.unassign');
    Route::post('/shifts/bulk-assign', [App\Http\Controllers\ShiftController::class, 'bulkAssign'])->name('shifts.bulk-assign');
    Route::post('/shifts/copy-week', [App\Http\Controllers\ShiftController::class, 'copyWeek'])->name('shifts.copy-week');
    Route::post('/shifts/clear-week', [App\Http\Controllers\ShiftController::class, 'clearWeek'])->name('shifts.clear-week');
    Route::get('/shifts/rotations', [App\Http\Controllers\ShiftController::class, 'rotations'])->name('shifts.rotations');
    Route::post('/shifts/rotations', [App\Http\Controllers\ShiftController::class, 'rotationsStore'])->name('shifts.rotations.store');
    Route::delete('/shifts/rotations/{rotation}', [App\Http\Controllers\ShiftController::class, 'rotationsDestroy'])->name('shifts.rotations.destroy');

    // Leave
    Route::get('/leave/types', [App\Http\Controllers\LeaveController::class, 'typesIndex'])->name('leave.types');
    Route::post('/leave/types', [App\Http\Controllers\LeaveController::class, 'typesStore'])->name('leave.types.store');
    Route::put('/leave/types/{type}', [App\Http\Controllers\LeaveController::class, 'typesUpdate'])->name('leave.types.update');
    Route::delete('/leave/types/{type}', [App\Http\Controllers\LeaveController::class, 'typesDestroy'])->name('leave.types.destroy');
    Route::get('/leave/requests', [App\Http\Controllers\LeaveController::class, 'requestsIndex'])->name('leave.requests');
    Route::post('/leave/requests', [App\Http\Controllers\LeaveController::class, 'requestsStore'])->name('leave.requests.store');
    Route::post('/leave/requests/{leaveRequest}/approve', [App\Http\Controllers\LeaveController::class, 'requestsApprove'])->name('leave.requests.approve');
    Route::post('/leave/requests/{leaveRequest}/reject', [App\Http\Controllers\LeaveController::class, 'requestsReject'])->name('leave.requests.reject');
    Route::post('/leave/requests/{leaveRequest}/cancel', [App\Http\Controllers\LeaveController::class, 'requestsCancel'])->name('leave.requests.cancel');
    Route::get('/leave/balances', [App\Http\Controllers\LeaveController::class, 'balances'])->name('leave.balances');
    Route::post('/leave/balances', [App\Http\Controllers\LeaveController::class, 'balancesStore'])->name('leave.balances.store');
    Route::put('/leave/balances/{balance}', [App\Http\Controllers\LeaveController::class, 'balancesUpdate'])->name('leave.balances.update');
    Route::delete('/leave/balances/{balance}', [App\Http\Controllers\LeaveController::class, 'balancesDestroy'])->name('leave.balances.destroy');
    Route::get('/leave/employee/{employee}/history', [App\Http\Controllers\LeaveController::class, 'employeeHistory'])->name('leave.employee.history');

    // Payroll (admin/owner only)
    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/payroll/components', [App\Http\Controllers\PayrollController::class, 'componentsIndex'])->name('payroll.components');
    Route::post('/payroll/components', [App\Http\Controllers\PayrollController::class, 'componentsStore'])->name('payroll.components.store');
    Route::put('/payroll/components/{component}', [App\Http\Controllers\PayrollController::class, 'componentsUpdate'])->name('payroll.components.update');
    Route::delete('/payroll/components/{component}', [App\Http\Controllers\PayrollController::class, 'componentsDestroy'])->name('payroll.components.destroy');
    Route::get('/payroll/runs', [App\Http\Controllers\PayrollController::class, 'runsIndex'])->name('payroll.runs');
    Route::post('/payroll/runs', [App\Http\Controllers\PayrollController::class, 'runsStore'])->name('payroll.runs.store');
    Route::get('/payroll/runs/{run}', [App\Http\Controllers\PayrollController::class, 'runsShow'])->name('payroll.runs.show');
    Route::post('/payroll/runs/{run}/process', [App\Http\Controllers\PayrollController::class, 'runsProcess'])->name('payroll.runs.process');
    Route::post('/payroll/runs/{run}/approve', [App\Http\Controllers\PayrollController::class, 'runsApprove'])->name('payroll.runs.approve');
    Route::post('/payroll/runs/{run}/mark-paid', [App\Http\Controllers\PayrollController::class, 'runsMarkPaid'])->name('payroll.runs.mark-paid');
    Route::get('/payroll/payslips/{payslip}', [App\Http\Controllers\PayrollController::class, 'payslipsShow'])->name('payroll.payslips.show');
    Route::get('/payroll/bonuses', [App\Http\Controllers\PayrollController::class, 'bonusesIndex'])->name('payroll.bonuses');
    Route::post('/payroll/bonuses', [App\Http\Controllers\PayrollController::class, 'bonusesStore'])->name('payroll.bonuses.store');
    Route::delete('/payroll/bonuses/{bonus}', [App\Http\Controllers\PayrollController::class, 'bonusesDestroy'])->name('payroll.bonuses.destroy');
    Route::get('/payroll/commissions', [App\Http\Controllers\PayrollController::class, 'commissionsIndex'])->name('payroll.commissions');
    Route::post('/payroll/commissions', [App\Http\Controllers\PayrollController::class, 'commissionsStore'])->name('payroll.commissions.store');
    Route::delete('/payroll/commissions/{commission}', [App\Http\Controllers\PayrollController::class, 'commissionsDestroy'])->name('payroll.commissions.destroy');
    });

    // Recruitment
    Route::get('/recruitment/jobs', [App\Http\Controllers\RecruitmentController::class, 'jobsIndex'])->name('recruitment.jobs');
    Route::post('/recruitment/jobs', [App\Http\Controllers\RecruitmentController::class, 'jobsStore'])->name('recruitment.jobs.store');
    Route::put('/recruitment/jobs/{job}', [App\Http\Controllers\RecruitmentController::class, 'jobsUpdate'])->name('recruitment.jobs.update');
    Route::delete('/recruitment/jobs/{job}', [App\Http\Controllers\RecruitmentController::class, 'jobsDestroy'])->name('recruitment.jobs.destroy');
    Route::get('/recruitment/applicants', [App\Http\Controllers\RecruitmentController::class, 'applicantsIndex'])->name('recruitment.applicants');
    Route::post('/recruitment/applicants', [App\Http\Controllers\RecruitmentController::class, 'applicantsStore'])->name('recruitment.applicants.store');
    Route::get('/recruitment/applicants/{applicant}', [App\Http\Controllers\RecruitmentController::class, 'applicantsShow'])->name('recruitment.applicants.show');
    Route::post('/recruitment/applicants/{applicant}/stage', [App\Http\Controllers\RecruitmentController::class, 'applicantsUpdateStage'])->name('recruitment.applicants.stage');
    Route::delete('/recruitment/applicants/{applicant}', [App\Http\Controllers\RecruitmentController::class, 'applicantsDestroy'])->name('recruitment.applicants.destroy');
    Route::get('/recruitment/interviews', [App\Http\Controllers\RecruitmentController::class, 'interviewsIndex'])->name('recruitment.interviews');
    Route::post('/recruitment/interviews', [App\Http\Controllers\RecruitmentController::class, 'interviewsStore'])->name('recruitment.interviews.store');
    Route::put('/recruitment/interviews/{interview}', [App\Http\Controllers\RecruitmentController::class, 'interviewsUpdate'])->name('recruitment.interviews.update');
    Route::get('/recruitment/onboarding', [App\Http\Controllers\RecruitmentController::class, 'onboardingIndex'])->name('recruitment.onboarding');
    Route::post('/recruitment/onboarding/{task}/toggle', [App\Http\Controllers\RecruitmentController::class, 'onboardingToggleTask'])->name('recruitment.onboarding.toggle');

    // Performance
    Route::get('/performance/kpis', [App\Http\Controllers\PerformanceController::class, 'kpisIndex'])->name('performance.kpis');
    Route::post('/performance/kpis', [App\Http\Controllers\PerformanceController::class, 'kpisStore'])->name('performance.kpis.store');
    Route::put('/performance/kpis/{kpi}', [App\Http\Controllers\PerformanceController::class, 'kpisUpdate'])->name('performance.kpis.update');
    Route::delete('/performance/kpis/{kpi}', [App\Http\Controllers\PerformanceController::class, 'kpisDestroy'])->name('performance.kpis.destroy');
    Route::get('/performance/targets', [App\Http\Controllers\PerformanceController::class, 'targetsIndex'])->name('performance.targets');
    Route::post('/performance/targets', [App\Http\Controllers\PerformanceController::class, 'targetsStore'])->name('performance.targets.store');
    Route::delete('/performance/targets/{target}', [App\Http\Controllers\PerformanceController::class, 'targetsDestroy'])->name('performance.targets.destroy');
    Route::get('/performance/evaluations', [App\Http\Controllers\PerformanceController::class, 'evaluationsIndex'])->name('performance.evaluations');
    Route::post('/performance/evaluations/generate', [App\Http\Controllers\PerformanceController::class, 'evaluationsGenerate'])->name('performance.evaluations.generate');
    Route::get('/performance/evaluations/{evaluation}', [App\Http\Controllers\PerformanceController::class, 'evaluationsShow'])->name('performance.evaluations.show');
    Route::post('/performance/evaluations/{evaluation}/finalize', [App\Http\Controllers\PerformanceController::class, 'evaluationsFinalize'])->name('performance.evaluations.finalize');
    Route::get('/performance/leaderboard', [App\Http\Controllers\PerformanceController::class, 'leaderboard'])->name('performance.leaderboard');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'overview'])->name('analytics.overview');
    Route::get('/analytics/data-entry', [App\Http\Controllers\AnalyticsController::class, 'dataEntry'])->name('analytics.data-entry');
    Route::post('/analytics/data-entry', [App\Http\Controllers\AnalyticsController::class, 'dataEntry'])->name('analytics.data-entry.store');


    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/employees', [App\Http\Controllers\ReportController::class, 'employees'])->name('reports.employees');
    Route::get('/reports/attendance', [App\Http\Controllers\ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/payroll', [App\Http\Controllers\ReportController::class, 'payroll'])->name('reports.payroll');
    Route::get('/reports/recruitment', [App\Http\Controllers\ReportController::class, 'recruitment'])->name('reports.recruitment');
    Route::get('/reports/performance', [App\Http\Controllers\ReportController::class, 'performance'])->name('reports.performance');
    Route::get('/reports/call-center', [App\Http\Controllers\ReportController::class, 'callCenter'])->name('reports.call-center');
    Route::get('/reports/audit', [App\Http\Controllers\ReportController::class, 'audit'])->name('reports.audit');

    // Report Exports (PDF + Excel)
    Route::get('/reports/employees/export/{format}', [App\Http\Controllers\ReportController::class, 'exportEmployees'])->name('reports.employees.export');
    Route::get('/reports/attendance/export/{format}', [App\Http\Controllers\ReportController::class, 'exportAttendance'])->name('reports.attendance.export');
    Route::get('/reports/payroll/export/{format}', [App\Http\Controllers\ReportController::class, 'exportPayroll'])->name('reports.payroll.export');
    Route::get('/reports/recruitment/export/{format}', [App\Http\Controllers\ReportController::class, 'exportRecruitment'])->name('reports.recruitment.export');
    Route::get('/reports/performance/export/{format}', [App\Http\Controllers\ReportController::class, 'exportPerformance'])->name('reports.performance.export');
    Route::get('/reports/call-center/export/{format}', [App\Http\Controllers\ReportController::class, 'exportCallCenter'])->name('reports.call-center.export');
    Route::get('/reports/audit/export/{format}', [App\Http\Controllers\ReportController::class, 'exportAudit'])->name('reports.audit.export');
    Route::get('/reports/audit-report/download/{format}', [App\Http\Controllers\ReportController::class, 'downloadAuditReport'])->name('reports.audit-report.download');
    Route::get('/reports/guide/download', [App\Http\Controllers\ReportController::class, 'downloadGuide'])->name('reports.guide.download');
    Route::get('/reports/system-overview', [App\Http\Controllers\ReportController::class, 'systemOverview'])->name('reports.system-overview');
});


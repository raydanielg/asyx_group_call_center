<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Employee;
use App\Models\OnboardingChecklist;
use App\Models\EmployeeOnboarding;
use App\Models\EmployeeOnboardingTask;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    use AjaxResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Job Positions
    public function jobsIndex()
    {
        $jobs = JobPosition::with(['department', 'applicants'])->orderBy('created_at', 'desc')->paginate(15);
        $departments = \App\Models\Department::where('is_active', true)->get();
        $positions = \App\Models\Position::where('is_active', true)->get();
        return view('recruitment.jobs', compact('jobs', 'departments', 'positions'));
    }

    public function jobsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'openings' => 'required|integer|min:1',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
            'closes_at' => 'nullable|date',
        ]);

        $validated['status'] = 'open';
        $validated['opened_at'] = now();
        $validated['created_by'] = auth()->id();

        JobPosition::create($validated);
        return $this->ajaxSuccess('Job position created successfully.');
    }

    public function jobsUpdate(Request $request, JobPosition $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'openings' => 'required|integer|min:1',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0',
            'closes_at' => 'nullable|date',
            'status' => 'required|in:draft,open,on_hold,closed',
        ]);
        $job->update($validated);
        return $this->ajaxSuccess('Job position updated successfully.');
    }

    public function jobsDestroy(JobPosition $job)
    {
        $job->delete();
        return $this->ajaxSuccess('Job position deleted successfully.');
    }

    // Applicants
    public function applicantsIndex(Request $request)
    {
        $query = Applicant::with('jobPosition');

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        if ($request->filled('job_id')) {
            $query->where('job_position_id', $request->job_id);
        }

        $applicants = $query->orderBy('created_at', 'desc')->paginate(20);
        $jobs = JobPosition::where('status', 'open')->get();

        $stageCounts = [
            'applied' => Applicant::where('stage', 'applied')->count(),
            'screening' => Applicant::where('stage', 'screening')->count(),
            'interview' => Applicant::where('stage', 'interview')->count(),
            'offer' => Applicant::where('stage', 'offer')->count(),
            'hired' => Applicant::where('stage', 'hired')->count(),
            'rejected' => Applicant::where('stage', 'rejected')->count(),
        ];

        return view('recruitment.applicants', compact('applicants', 'jobs', 'stageCounts'));
    }

    public function applicantsStore(Request $request)
    {
        $validated = $request->validate([
            'job_position_id' => 'required|exists:job_positions,id',
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'source' => 'required|in:referral,walk_in,agency,online,other',
            'cover_letter' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);
        $validated['created_by'] = auth()->id();
        Applicant::create($validated);
        return $this->ajaxSuccess('Applicant added successfully.');
    }

    public function applicantsShow(Applicant $applicant)
    {
        $applicant->load(['jobPosition', 'interviews.interviewer']);
        return view('recruitment.applicant-show', compact('applicant'));
    }

    public function applicantsUpdateStage(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'stage' => 'required|in:applied,screening,interview,offer,hired,rejected',
            'rejected_reason' => 'nullable|string',
        ]);

        $oldStage = $applicant->stage;
        $applicant->update($validated);

        if ($validated['stage'] === 'hired' && $oldStage !== 'hired') {
            $code = 'EMP-' . str_pad(Employee::max('id') + 1, 4, '0', STR_PAD_LEFT);
            $employee = Employee::create([
                'employee_code' => $code,
                'first_name' => $applicant->first_name,
                'last_name' => $applicant->last_name,
                'email' => $applicant->email,
                'phone' => $applicant->phone,
                'department_id' => $applicant->jobPosition->department_id,
                'position_id' => $applicant->jobPosition->position_id,
                'employment_type' => $applicant->jobPosition->employment_type,
                'hire_date' => now()->toDateString(),
                'employment_status' => 'probation',
                'created_by' => auth()->id(),
            ]);

            // Decrement openings
            $job = $applicant->jobPosition;
            $job->decrement('openings');
            if ($job->openings <= 0) {
                $job->update(['status' => 'closed']);
            }

            // Create onboarding
            $checklist = OnboardingChecklist::where('is_default', true)->first();
            if ($checklist) {
                $onboarding = EmployeeOnboarding::create([
                    'employee_id' => $employee->id,
                    'checklist_id' => $checklist->id,
                    'status' => 'in_progress',
                ]);
                foreach ($checklist->tasks as $task) {
                    EmployeeOnboardingTask::create([
                        'onboarding_id' => $onboarding->id,
                        'task_title' => $task->title,
                    ]);
                }
            }
        }

        return $this->ajaxSuccess('Applicant stage updated successfully.');
    }

    public function applicantsDestroy(Applicant $applicant)
    {
        $applicant->delete();
        return $this->ajaxSuccess('Applicant deleted successfully.');
    }

    // Interviews
    public function interviewsIndex()
    {
        $interviews = Interview::with(['applicant', 'interviewer'])->orderBy('scheduled_at', 'desc')->paginate(20);
        return view('recruitment.interviews', compact('interviews'));
    }

    public function interviewsStore(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'round' => 'required|integer|min:1',
            'type' => 'required|in:phone,onsite,video,assessment',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:5',
            'interviewer_user_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:200',
        ]);
        Interview::create($validated);
        return $this->ajaxSuccess('Interview scheduled successfully.');
    }

    public function interviewsUpdate(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'score' => 'nullable|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);
        $interview->update($validated);
        return $this->ajaxSuccess('Interview updated successfully.');
    }

    // Onboarding
    public function onboardingIndex()
    {
        $onboardings = EmployeeOnboarding::with(['employee', 'checklist', 'tasks'])
            ->orderBy('started_at', 'desc')->paginate(15);
        return view('recruitment.onboarding', compact('onboardings'));
    }

    public function onboardingToggleTask(EmployeeOnboardingTask $task)
    {
        $task->update([
            'is_done' => !$task->is_done,
            'done_at' => $task->is_done ? null : now(),
            'done_by' => $task->is_done ? null : auth()->id(),
        ]);

        $onboarding = $task->onboarding;
        $allDone = $onboarding->tasks()->where('is_done', false)->count() === 0;
        if ($allDone) {
            $onboarding->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return $this->ajaxSuccess('Task updated successfully.');
    }
}

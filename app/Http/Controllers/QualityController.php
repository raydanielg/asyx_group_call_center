<?php

namespace App\Http\Controllers;

use App\Models\QualityEvaluationForm;
use App\Models\QualityFormCriterion;
use App\Models\QualityEvaluation;
use App\Models\CoachingNote;
use App\Models\Employee;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    use AjaxResponseTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Forms
    public function formsIndex()
    {
        $forms = QualityEvaluationForm::with('criteria')->orderBy('name')->paginate(15);
        return view('quality.forms', compact('forms'));
    }

    public function formsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1',
        ]);
        QualityEvaluationForm::create($validated);
        return $this->ajaxSuccess('Form created successfully.');
    }

    public function formsShow(QualityEvaluationForm $form)
    {
        $form->load('criteria');
        return view('quality.form-show', compact('form'));
    }

    public function formsStoreCriterion(Request $request, QualityEvaluationForm $form)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:200',
            'weight' => 'required|numeric|min:0',
            'max_points' => 'required|integer|min:1',
        ]);
        $validated['form_id'] = $form->id;
        $validated['sort_order'] = $form->criteria()->max('sort_order') + 1;
        QualityFormCriterion::create($validated);
        return $this->ajaxSuccess('Criterion added successfully.');
    }

    public function formsDestroyCriterion(QualityFormCriterion $criterion)
    {
        $criterion->delete();
        return $this->ajaxSuccess('Criterion deleted successfully.');
    }

    public function formsDestroy(QualityEvaluationForm $form)
    {
        $form->delete();
        return $this->ajaxSuccess('Form deleted successfully.');
    }

    // Evaluations
    public function evaluationsIndex()
    {
        $evaluations = QualityEvaluation::with(['employee', 'form', 'evaluator'])->orderBy('evaluated_at', 'desc')->paginate(15);
        return view('quality.evaluations', compact('evaluations'));
    }

    public function evaluationsCreate()
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $forms = QualityEvaluationForm::where('is_active', true)->get();
        return view('quality.evaluation-create', compact('employees', 'forms'));
    }

    public function evaluationsStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'form_id' => 'required|exists:quality_evaluation_forms,id',
            'call_reference' => 'nullable|string|max:100',
            'scores' => 'required|array',
            'summary' => 'nullable|string',
        ]);

        $form = QualityEvaluationForm::with('criteria')->find($validated['form_id']);
        $scores = $validated['scores'];
        $totalScore = 0;
        $maxTotal = 0;

        foreach ($form->criteria as $criterion) {
            $points = $scores[$criterion->id] ?? 0;
            $totalScore += $points;
            $maxTotal += $criterion->max_points;
        }

        $percentage = $maxTotal > 0 ? round(($totalScore / $maxTotal) * 100, 2) : 0;
        $outcome = $percentage >= 80 ? 'pass' : ($percentage >= 60 ? 'coaching_required' : 'fail');

        $eval = QualityEvaluation::create([
            'employee_id' => $validated['employee_id'],
            'form_id' => $validated['form_id'],
            'call_reference' => $validated['call_reference'],
            'evaluator_user_id' => auth()->id(),
            'scores' => $scores,
            'total_score' => $totalScore,
            'percentage' => $percentage,
            'outcome' => $outcome,
            'summary' => $validated['summary'],
        ]);

        if ($outcome !== 'pass') {
            return $this->ajaxSuccess('Evaluation saved. Consider adding a coaching note.', route('quality.coaching.create', ['evaluation_id' => $eval->id]));
        }

        return $this->ajaxSuccess('Evaluation saved successfully.', route('quality.evaluations'));
    }

    public function evaluationsShow(QualityEvaluation $evaluation)
    {
        $evaluation->load(['employee', 'form.criteria', 'evaluator']);
        return view('quality.evaluation-show', compact('evaluation'));
    }

    // Coaching Notes
    public function coachingIndex()
    {
        $notes = CoachingNote::with(['employee', 'qualityEvaluation', 'createdBy'])->orderBy('created_at', 'desc')->paginate(15);
        return view('quality.coaching', compact('notes'));
    }

    public function coachingCreate(Request $request)
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $evaluationId = $request->get('evaluation_id');
        return view('quality.coaching-create', compact('employees', 'evaluationId'));
    }

    public function coachingStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'quality_evaluation_id' => 'nullable|exists:quality_evaluations,id',
            'note' => 'required|string',
            'action_items' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';
        CoachingNote::create($validated);
        return $this->ajaxSuccess('Coaching note added successfully.', route('quality.coaching'));
    }

    public function coachingDone(CoachingNote $note)
    {
        $note->update(['status' => 'done']);
        return $this->ajaxSuccess('Coaching note marked as done successfully.');
    }
}

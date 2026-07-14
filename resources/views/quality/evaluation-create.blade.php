@extends('layouts.dashboard')

@section('title', 'New QA Evaluation - AYS Call Center')
@section('page_title', 'Quality · New Evaluation')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('quality.evaluations') }}" class="text-xs text-gray-400 hover:text-navy-600">Evaluations</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">New</span>
    </div>
</div>

<form method="POST" action="{{ route('quality.evaluations.store') }}" class="space-y-4" id="qa-form">
    @csrf

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Evaluation Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Agent <span class="text-red-500">*</span></label>
                <select name="employee_id" id="agent-select" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Form <span class="text-red-500">*</span></label>
                <select name="form_id" id="form-select" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($forms as $f)<option value="{{ $f->id }}">{{ $f->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Call Reference</label>
                <input type="text" name="call_reference" placeholder="e.g. CALL-2024-001" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Scoring</h3>
        <div id="criteria-container" class="space-y-3">
            <p class="text-xs text-gray-400">Select a form to load criteria.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <label class="block text-xs font-medium text-gray-600 mb-1">Summary</label>
        <textarea name="summary" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Save Evaluation</button>
        <a href="{{ route('quality.evaluations') }}" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
    </div>
</form>

<script>
const formData = @json($forms->mapWithKeys(fn($f) => [$f->id => $f->criteria]));

document.getElementById('form-select').addEventListener('change', function() {
    const formId = this.value;
    const container = document.getElementById('criteria-container');
    if (!formId || !formData[formId]) {
        container.innerHTML = '<p class="text-xs text-gray-400">Select a form to load criteria.</p>';
        return;
    }
    const criteria = formData[formId];
    container.innerHTML = criteria.map(c => `
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="text-xs font-medium text-gray-900">${c.label}</div>
                <div class="text-[10px] text-gray-400">Max: ${c.max_points} pts · Weight: ${c.weight}</div>
            </div>
            <input type="number" name="scores[${c.id}]" min="0" max="${c.max_points}" placeholder="0" required
                class="w-20 px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 text-center">
        </div>
    `).join('');
});
</script>

@endsection

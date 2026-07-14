@extends('layouts.dashboard')

@section('title', 'QA Evaluation - AYS Call Center')
@section('page_title', 'Quality · Evaluation')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('quality.evaluations') }}" class="text-xs text-gray-400 hover:text-navy-600">Evaluations</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">Details</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Summary --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <div class="text-center py-4">
                @php $outColors = ['pass'=>'green','coaching_required'=>'amber','fail'=>'red']; @endphp
                @php $color = $outColors[$evaluation->outcome] ?? 'gray'; @endphp
                <div class="text-3xl font-bold text-{{ $color }}-600">{{ $evaluation->percentage }}%</div>
                <div class="text-xs text-gray-500 mt-1">{{ $evaluation->total_score }} / {{ $evaluation->form?->max_score ?? 100 }}</div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100 mt-2">{{ ucfirst(str_replace('_',' ',$evaluation->outcome)) }}</span>
            </div>
            <dl class="space-y-2 text-xs mt-3">
                <div class="flex justify-between"><dt class="text-gray-500">Agent</dt><dd class="text-gray-900 font-medium">{{ $evaluation->employee?->first_name }} {{ $evaluation->employee?->last_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Form</dt><dd class="text-gray-900 font-medium">{{ $evaluation->form?->name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Call Ref</dt><dd class="text-gray-900 font-medium">{{ $evaluation->call_reference ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Evaluator</dt><dd class="text-gray-900 font-medium">{{ $evaluation->evaluator?->name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Date</dt><dd class="text-gray-900 font-medium">{{ $evaluation->evaluated_at?->format('M d, Y') }}</dd></div>
            </dl>
            @if($evaluation->outcome !== 'pass')
            <a href="{{ route('quality.coaching.create', ['evaluation_id' => $evaluation->id]) }}" class="block mt-4 w-full px-4 py-2 text-xs font-medium text-center bg-copper-600 text-white rounded-lg hover:bg-copper-700">Add Coaching Note</a>
            @endif
        </div>
    </div>

    {{-- Scores --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Score Breakdown</h3>
            @php $scores = is_string($evaluation->scores) ? json_decode($evaluation->scores, true) : $evaluation->scores; @endphp
            <div class="space-y-2">
                @foreach($evaluation->form?->criteria ?? [] as $c)
                @php $score = $scores[$c->id] ?? 0; $pct = $c->max_points > 0 ? ($score / $c->max_points) * 100 : 0; @endphp
                <div class="border border-gray-100 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-medium text-gray-900">{{ $c->label }}</span>
                        <span class="text-xs font-medium {{ $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-amber-600' : 'text-red-500') }}">{{ $score }}/{{ $c->max_points }}</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($evaluation->summary)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <h4 class="text-xs font-semibold text-gray-900 mb-2">Summary</h4>
                <p class="text-xs text-gray-600">{{ $evaluation->summary }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

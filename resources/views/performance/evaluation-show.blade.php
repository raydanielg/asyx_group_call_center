@extends('layouts.dashboard')

@section('title', 'Evaluation - AYS Call Center')
@section('page_title', 'Performance · Evaluation')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('performance.evaluations') }}" class="text-xs text-gray-400 hover:text-navy-600">Evaluations</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">{{ $evaluation->employee?->first_name }} {{ $evaluation->employee?->last_name }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Left: Summary --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Evaluation Summary</h3>
            <div class="text-center py-4">
                @php $gradeColors = ['A'=>'green','B'=>'sky','C'=>'amber','D'=>'copper','E'=>'red']; @endphp
                @php $gc = $gradeColors[$evaluation->grade] ?? 'gray'; @endphp
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full text-2xl font-bold bg-{{ $gc }}-50 text-{{ $gc }}-700 border-2 border-{{ $gc }}-200">{{ $evaluation->grade }}</div>
                <div class="text-3xl font-bold text-gray-900 mt-2">{{ $evaluation->weighted_score }}</div>
                <div class="text-xs text-gray-500">Weighted Score</div>
            </div>
            <dl class="space-y-2 text-xs mt-3">
                <div class="flex justify-between"><dt class="text-gray-500">Employee</dt><dd class="text-gray-900 font-medium">{{ $evaluation->employee?->first_name }} {{ $evaluation->employee?->last_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Department</dt><dd class="text-gray-900 font-medium">{{ $evaluation->employee?->department?->name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Period</dt><dd class="text-gray-900 font-medium">{{ $evaluation->period_year }}/{{ str_pad($evaluation->period_month, 2, '0', STR_PAD_LEFT) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Evaluated By</dt><dd class="text-gray-900 font-medium">{{ $evaluation->evaluatedBy?->name ?? 'System' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $evaluation->status === 'finalized' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($evaluation->status) }}</span></dd></div>
            </dl>
            @if($evaluation->status !== 'finalized')
            <form method="POST" action="{{ route('performance.evaluations.finalize', $evaluation) }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700">Finalize</button>
            </form>
            @endif
        </div>
    </div>

    {{-- Right: KPI Breakdown --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">KPI Breakdown</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                            <th class="px-3 py-2 font-medium">KPI</th>
                            <th class="px-3 py-2 font-medium">Target</th>
                            <th class="px-3 py-2 font-medium">Actual</th>
                            <th class="px-3 py-2 font-medium">Score</th>
                            <th class="px-3 py-2 font-medium">Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $scores = is_string($evaluation->kpi_scores) ? json_decode($evaluation->kpi_scores, true) : $evaluation->kpi_scores; @endphp
                        @forelse($scores ?? [] as $ks)
                        <tr class="border-t border-gray-100">
                            <td class="px-3 py-2.5 font-medium text-gray-900 text-xs">{{ $ks['kpi_name'] ?? 'N/A' }}</td>
                            <td class="px-3 py-2.5 text-gray-500 text-xs">{{ $ks['target'] ?? 0 }}</td>
                            <td class="px-3 py-2.5 text-gray-500 text-xs">{{ $ks['actual'] ?? 0 }}</td>
                            <td class="px-3 py-2.5 font-medium text-xs {{ ($ks['score'] ?? 0) >= 80 ? 'text-green-600' : ($ks['score'] >= 60 ? 'text-amber-600' : 'text-red-500') }}">{{ $ks['score'] ?? 0 }}</td>
                            <td class="px-3 py-2.5">
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden w-24">
                                    <div class="h-full rounded-full {{ ($ks['score'] ?? 0) >= 80 ? 'bg-green-500' : ($ks['score'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ min($ks['score'] ?? 0, 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-3 py-8 text-center text-gray-400 text-xs">No KPI scores available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

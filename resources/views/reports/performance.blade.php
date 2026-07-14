@extends('layouts.dashboard')

@section('title', 'Performance Report - AYS Call Center')
@section('page_title', 'Reports · Performance')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Performance Report</h2>
        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
    </div>
    <form method="GET" action="{{ route('reports.performance') }}" class="flex items-center gap-2">
        <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 w-20">
        <select name="month" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            @for($m = 1; $m <= 12; $m++)<option value="{{ $m }}" @selected($m == $month)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>@endfor
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    {{-- Avg Score --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="text-center py-4">
            <div class="text-3xl font-bold text-navy-600">{{ round($avgScore ?? 0, 1) }}</div>
            <div class="text-xs text-gray-500 mt-1">Average Score</div>
        </div>
    </div>

    {{-- Grade Distribution --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Grade Distribution</h3>
        <div class="flex items-end gap-4 h-32">
            @php $grades = ['A','B','C','D','E']; $gradeMax = max($gradeDist ?: [1]); @endphp
            @foreach($grades as $g)
            @php $count = $gradeDist[$g] ?? 0; $pct = ($count / $gradeMax) * 100; $colors = ['A'=>'green','B'=>'sky','C'=>'amber','D'=>'copper','E'=>'red']; $gc = $colors[$g]; @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs font-bold text-gray-900">{{ $count }}</span>
                <div class="w-full bg-gray-50 rounded-t-md relative h-24 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-{{ $gc }}-500" style="height: {{ max($pct, 2) }}%"></div>
                </div>
                <span class="text-xs font-bold text-{{ $gc }}-600">{{ $g }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Performers --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">Top Performers</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">#</th>
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Department</th>
                    <th class="px-5 py-3 font-medium">Score</th>
                    <th class="px-5 py-3 font-medium">Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPerformers as $i => $ev)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 text-gray-400 font-bold text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ev->employee?->first_name ?? '' }} {{ $ev->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->employee?->department?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ev->weighted_score }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold bg-gray-50 text-gray-700 border border-gray-200">{{ $ev->grade }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No evaluations for this period</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@extends('layouts.dashboard')

@section('title', 'Leaderboard - AYS Call Center')
@section('page_title', 'Performance · Leaderboard')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Performance Leaderboard</h2>
        <p class="text-xs text-gray-500">Top performers for {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
    </div>
    <form method="GET" action="{{ route('performance.leaderboard') }}" class="flex items-center gap-2">
        <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 w-20">
        <select name="month" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            @for($m = 1; $m <= 12; $m++)<option value="{{ $m }}" @selected($m == $month)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>@endfor
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="space-y-2">
    @forelse($evaluations as $i => $ev)
    @php $rank = $i + 1; @endphp
    @php $gradeColors = ['A'=>'green','B'=>'sky','C'=>'amber','D'=>'copper','E'=>'red']; @endphp
    @php $gc = $gradeColors[$ev->grade] ?? 'gray'; @endphp
    <div class="bg-white rounded-xl border p-4 flex items-center gap-4 {{ $rank <= 3 ? 'border-{{ $gc }}-200' : '' }}">
        <div class="w-8 text-center">
            @if($rank === 1)
                <span class="text-lg">🥇</span>
            @elseif($rank === 2)
                <span class="text-lg">🥈</span>
            @elseif($rank === 3)
                <span class="text-lg">🥉</span>
            @else
                <span class="text-sm font-bold text-gray-400">{{ $rank }}</span>
            @endif
        </div>
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
            {{ strtoupper(substr($ev->employee?->first_name ?? 'A', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <a href="{{ route('performance.evaluations.show', $ev) }}" class="text-sm font-semibold text-gray-900 hover:text-navy-600">{{ $ev->employee?->first_name ?? '' }} {{ $ev->employee?->last_name ?? '' }}</a>
            <p class="text-xs text-gray-500">{{ $ev->employee?->department?->name ?? 'N/A' }} · {{ $ev->employee?->position?->title ?? 'N/A' }}</p>
        </div>
        <div class="text-right shrink-0">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-{{ $gc }}-50 text-{{ $gc }}-700 border border-{{ $gc }}-200">{{ $ev->grade }}</span>
                <span class="text-lg font-bold text-gray-900">{{ $ev->weighted_score }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border p-10 text-center text-gray-400">
        <p class="text-sm">No evaluations for this period</p>
        <p class="text-xs mt-1">Generate evaluations first from the Evaluations page.</p>
    </div>
    @endforelse
</div>

@endsection

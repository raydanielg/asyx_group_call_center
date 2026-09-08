@extends('layouts.dashboard')

@section('title', 'Call Center Report - AYS Call Center')
@section('page_title', 'Reports · Call Center')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Call Center Report</h2>
        <p class="text-xs text-gray-500">{{ $from }} to {{ $to }}</p>
    </div>
    <form method="GET" action="{{ route('reports.call-center') }}" class="flex items-center gap-2">
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('reports.call-center.export', 'pdf') }}?from={{ $from }}&to={{ $to }}" class="px-3 py-2 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>Export PDF</a>
    <a href="{{ route('reports.call-center.export', 'excel') }}?from={{ $from }}&to={{ $to }}" class="px-3 py-2 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Export Excel</a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total Calls</div><div class="text-xl font-bold text-navy-600 mt-1">{{ number_format($summary['total_calls']) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Answered</div><div class="text-xl font-bold text-green-600 mt-1">{{ number_format($summary['answered']) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Missed</div><div class="text-xl font-bold text-red-500 mt-1">{{ number_format($summary['missed']) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Conversions</div><div class="text-xl font-bold text-purple-600 mt-1">{{ number_format($summary['conversions']) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Avg AHT</div><div class="text-xl font-bold text-copper-600 mt-1">{{ $summary['avg_aht'] }}s</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Avg CSAT</div><div class="text-xl font-bold text-green-600 mt-1">{{ $summary['avg_csat'] }}</div></div>
</div>

{{-- Calls Trend Chart --}}
<div class="bg-white rounded-xl border p-5 mb-4">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Daily Calls Trend</h3>
    @php $callsMax = max($callsTrend ?: [1]); $tCount = count($trendLabels); @endphp
    <div class="flex items-end gap-1 h-40">
        @for($i = 0; $i < $tCount; $i++)
        @php $pct = ($callsTrend[$i] / $callsMax) * 100; @endphp
        <div class="flex-1 flex flex-col items-center" style="min-width: 6px;">
            <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-navy-600 to-navy-400" style="height: {{ max($pct, 2) }}%"></div>
            </div>
            @if($tCount <= 15 || $i % 5 === 0)<span class="text-[8px] text-gray-400 mt-1">{{ $trendLabels[$i] }}</span>@endif
        </div>
        @endfor
    </div>
</div>

{{-- AHT & CSAT Trends --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">AHT Trend (s)</h3>
        @php $ahtMax = max($ahtTrend ?: [1]); @endphp
        <div class="flex items-end gap-1 h-32">
            @for($i = 0; $i < $tCount; $i++)
            @php $pct = ($ahtTrend[$i] / $ahtMax) * 100; @endphp
            <div class="flex-1" style="min-width: 6px;">
                <div class="w-full bg-gray-50 rounded-t-md relative h-24 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-copper-500 to-copper-400" style="height: {{ max($pct, 2) }}%"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">CSAT Trend</h3>
        @php $csatMax = max($csatTrend ?: [5]); @endphp
        <div class="flex items-end gap-1 h-32">
            @for($i = 0; $i < $tCount; $i++)
            @php $pct = ($csatTrend[$i] / $csatMax) * 100; @endphp
            <div class="flex-1" style="min-width: 6px;">
                <div class="w-full bg-gray-50 rounded-t-md relative h-24 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-green-500 to-green-400" style="height: {{ max($pct, 2) }}%"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

{{-- By Employee --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">By Agent</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Agent</th>
                    <th class="px-5 py-3 font-medium text-right">Calls</th>
                    <th class="px-5 py-3 font-medium text-right">AHT</th>
                    <th class="px-5 py-3 font-medium text-right">CSAT</th>
                    <th class="px-5 py-3 font-medium text-right">Conv.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byEmployee as $row)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ number_format($row['calls']) }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $row['aht'] }}s</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $row['csat'] }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $row['conversions'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No data for this period</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

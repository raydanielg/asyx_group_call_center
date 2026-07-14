@extends('layouts.dashboard')

@section('title', 'Analytics - AYS Call Center')
@section('page_title', 'Analytics · Overview')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Call Center Analytics</h2>
        <p class="text-xs text-gray-500">Performance overview from {{ $from }} to {{ $to }}</p>
    </div>
    <form method="GET" action="{{ route('analytics.overview') }}" class="flex flex-wrap items-center gap-2">
        <select name="department_id" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Depts</option>
            @foreach($departments as $d)<option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>@endforeach
        </select>
        <select name="employee_id" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Agents</option>
            @foreach($employees as $e)<option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
        </select>
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

{{-- Summary KPIs --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Total Calls</div>
        <div class="text-2xl font-bold text-navy-600 mt-1">{{ number_format($summary['total_calls']) }}</div>
        <div class="text-[10px] text-gray-400 mt-0.5">{{ $summary['answer_rate'] }}% answered</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Avg AHT</div>
        <div class="text-2xl font-bold text-copper-600 mt-1">{{ $summary['avg_aht'] }}s</div>
        <div class="text-[10px] text-gray-400 mt-0.5">Handle time</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Avg CSAT</div>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $summary['avg_csat'] }}</div>
        <div class="text-[10px] text-gray-400 mt-0.5">Out of 5.0</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Conversions</div>
        <div class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($summary['conversions']) }}</div>
        <div class="text-[10px] text-gray-400 mt-0.5">{{ $summary['conversion_rate'] }}% rate</div>
    </div>
</div>

{{-- Calls Trend --}}
<div class="bg-white rounded-xl border p-5 mb-4">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Calls Trend</h3>
    @php $callsMax = max($callsData ?: [1]); $callsCount = count($trendLabels); @endphp
    <div class="flex items-end gap-1 h-40">
        @for($i = 0; $i < $callsCount; $i++)
            @php $pct = ($callsData[$i] / $callsMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1 group cursor-pointer" style="min-width: 6px;">
                <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-navy-600 to-navy-400" style="height: {{ max($pct, 2) }}%"></div>
                </div>
                @if($callsCount <= 15 || $i % 5 === 0)
                    <span class="text-[8px] text-gray-400 font-medium whitespace-nowrap">{{ $trendLabels[$i] }}</span>
                @endif
            </div>
        @endfor
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    {{-- AHT Trend --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">AHT Trend (seconds)</h3>
        @php $ahtMax = max($ahtData ?: [1]); $ahtCount = count($trendLabels); @endphp
        <div class="flex items-end gap-1 h-32">
            @for($i = 0; $i < $ahtCount; $i++)
                @php $pct = ($ahtData[$i] / $ahtMax) * 100; @endphp
                <div class="flex-1 flex flex-col items-center" style="min-width: 6px;">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-24 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-copper-500 to-copper-400" style="height: {{ max($pct, 2) }}%"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    {{-- CSAT Trend --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">CSAT Trend</h3>
        @php $csatMax = max($csatData ?: [5]); $csatCount = count($trendLabels); @endphp
        <div class="flex items-end gap-1 h-32">
            @for($i = 0; $i < $csatCount; $i++)
                @php $pct = ($csatData[$i] / $csatMax) * 100; @endphp
                <div class="flex-1 flex flex-col items-center" style="min-width: 6px;">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-24 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-green-500 to-green-400" style="height: {{ max($pct, 2) }}%"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

{{-- Team Comparison + Heatmap --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Team Comparison --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Team Comparison</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-50">
                        <th class="px-2 py-2 font-medium">Team</th>
                        <th class="px-2 py-2 font-medium text-right">Calls</th>
                        <th class="px-2 py-2 font-medium text-right">AHT</th>
                        <th class="px-2 py-2 font-medium text-right">CSAT</th>
                        <th class="px-2 py-2 font-medium text-right">Conv.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamComparison as $team => $data)
                    <tr class="border-b border-gray-50">
                        <td class="px-2 py-2 font-medium text-gray-900">{{ $team }}</td>
                        <td class="px-2 py-2 text-right text-gray-600">{{ number_format($data['calls']) }}</td>
                        <td class="px-2 py-2 text-right text-gray-600">{{ $data['aht'] }}s</td>
                        <td class="px-2 py-2 text-right text-gray-600">{{ $data['csat'] }}</td>
                        <td class="px-2 py-2 text-right text-gray-600">{{ $data['conversions'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Day of Week Heatmap --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Calls by Day of Week</h3>
        @php $heatMax = max($heatmap ?: [1]); @endphp
        <div class="space-y-2">
            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                @php $val = $heatmap[$day] ?? 0; $pct = ($val / $heatMax) * 100; @endphp
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium text-gray-600 w-10 shrink-0">{{ $day }}</span>
                    <div class="flex-1 h-6 bg-gray-50 rounded-lg overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-navy-400 to-navy-600 rounded-lg flex items-center px-2 transition-all duration-500" style="width: {{ max($pct, 2) }}%">
                            <span class="text-[10px] font-bold text-white">{{ number_format($val) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

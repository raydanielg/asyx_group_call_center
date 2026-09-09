@extends('layouts.dashboard')

@section('title', 'Attendance Summary - AYS Call Center')
@section('page_title', 'Attendance · Summary')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Attendance Summary</h2>
        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($from)->format('M d') }} — {{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</p>
    </div>
    <form method="GET" action="{{ route('attendance.summary') }}" class="flex flex-wrap items-center gap-2">
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="From date">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="To date">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors">Apply</button>
    </form>
</div>

@php $total = $summary['present'] + $summary['late'] + $summary['absent'] + $summary['half_day'] + $summary['on_leave'] + $summary['off']; @endphp

{{-- KPI Cards — soft, no borders, rounded --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
    <div class="bg-green-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-[10px] font-medium text-green-700 uppercase">Present</span>
        </div>
        <div class="text-2xl font-bold text-green-700">{{ $summary['present'] }}</div>
        <div class="text-[10px] text-green-500 mt-0.5">{{ $total > 0 ? round(($summary['present'] / $total) * 100) : 0 }}% of records</div>
    </div>
    <div class="bg-amber-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-medium text-amber-700 uppercase">Late</span>
        </div>
        <div class="text-2xl font-bold text-amber-700">{{ $summary['late'] }}</div>
        <div class="text-[10px] text-amber-500 mt-0.5">Avg {{ $summary['avg_late_minutes'] }}m late</div>
    </div>
    <div class="bg-red-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <span class="text-[10px] font-medium text-red-700 uppercase">Absent</span>
        </div>
        <div class="text-2xl font-bold text-red-700">{{ $summary['absent'] }}</div>
        <div class="text-[10px] text-red-500 mt-0.5">{{ $total > 0 ? round(($summary['absent'] / $total) * 100) : 0 }}% of records</div>
    </div>
    <div class="bg-sky-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-sky-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-medium text-sky-700 uppercase">Half Day</span>
        </div>
        <div class="text-2xl font-bold text-sky-700">{{ $summary['half_day'] }}</div>
        <div class="text-[10px] text-sky-500 mt-0.5">{{ $total > 0 ? round(($summary['half_day'] / $total) * 100) : 0 }}% of records</div>
    </div>
    <div class="bg-purple-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <span class="text-[10px] font-medium text-purple-700 uppercase">On Leave</span>
        </div>
        <div class="text-2xl font-bold text-purple-700">{{ $summary['on_leave'] }}</div>
        <div class="text-[10px] text-purple-500 mt-0.5">{{ $total > 0 ? round(($summary['on_leave'] / $total) * 100) : 0 }}% of records</div>
    </div>
    <div class="bg-copper-50 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full bg-copper-100 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-[10px] font-medium text-copper-700 uppercase">OT Hours</span>
        </div>
        <div class="text-2xl font-bold text-copper-700">{{ number_format($summary['total_ot_minutes'] / 60, 1) }}h</div>
        <div class="text-[10px] text-copper-500 mt-0.5">{{ $summary['total_ot_minutes'] }} min total</div>
    </div>
</div>

{{-- Distribution Bar --}}
@if($total > 0)
<div class="bg-white rounded-2xl p-5 mb-5">
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold text-gray-700">Status Distribution</span>
        <span class="text-xs text-gray-400">{{ $total }} total records</span>
    </div>
    <div class="flex h-3 rounded-full overflow-hidden gap-0.5">
        @if($summary['present'])<div class="bg-green-500" style="width: {{ ($summary['present'] / $total) * 100 }}%" title="Present: {{ $summary['present'] }}"></div>@endif
        @if($summary['late'])<div class="bg-amber-500" style="width: {{ ($summary['late'] / $total) * 100 }}%" title="Late: {{ $summary['late'] }}"></div>@endif
        @if($summary['absent'])<div class="bg-red-500" style="width: {{ ($summary['absent'] / $total) * 100 }}%" title="Absent: {{ $summary['absent'] }}"></div>@endif
        @if($summary['half_day'])<div class="bg-sky-500" style="width: {{ ($summary['half_day'] / $total) * 100 }}%" title="Half Day: {{ $summary['half_day'] }}"></div>@endif
        @if($summary['on_leave'])<div class="bg-purple-500" style="width: {{ ($summary['on_leave'] / $total) * 100 }}%" title="On Leave: {{ $summary['on_leave'] }}"></div>@endif
        @if($summary['off'])<div class="bg-gray-400" style="width: {{ ($summary['off'] / $total) * 100 }}%" title="Off: {{ $summary['off'] }}"></div>@endif
    </div>
    <div class="flex flex-wrap items-center gap-4 mt-3">
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>Present</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Late</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Absent</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>Half Day</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>On Leave</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>Off</span>
    </div>
</div>
@endif

{{-- Per Employee --}}
<div class="bg-white rounded-2xl overflow-hidden">
    <div class="px-5 py-3.5 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Per Employee Breakdown</h3>
        <span class="text-xs text-gray-400">{{ $byEmployee->count() }} employees</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium text-right">Present</th>
                    <th class="px-5 py-3 font-medium text-right">Late</th>
                    <th class="px-5 py-3 font-medium text-right">Absent</th>
                    <th class="px-5 py-3 font-medium text-right">Half Day</th>
                    <th class="px-5 py-3 font-medium text-right">OT (h)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byEmployee as $row)
                @php $empTotal = $row['present'] + $row['late'] + $row['absent']; @endphp
                <tr class="border-t border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                {{ strtoupper(substr($row['employee']?->first_name ?? 'A', 0, 1) . substr($row['employee']?->last_name ?? '', 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900 text-xs">{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <span class="inline-flex items-center justify-center min-w-[28px] px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">{{ $row['present'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <span class="inline-flex items-center justify-center min-w-[28px] px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">{{ $row['late'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <span class="inline-flex items-center justify-center min-w-[28px] px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700">{{ $row['absent'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-right text-xs text-gray-400">{{ $row['half_day'] ?? 0 }}</td>
                    <td class="px-5 py-3 text-right text-xs font-medium text-copper-600">{{ number_format($row['ot_minutes'] / 60, 1) }}h</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">No attendance data</p>
                                <p class="text-xs text-gray-500 mt-1">No records found for this period</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

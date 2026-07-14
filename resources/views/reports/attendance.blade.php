@extends('layouts.dashboard')

@section('title', 'Attendance Report - AYS Call Center')
@section('page_title', 'Reports · Attendance')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Attendance Report</h2>
        <p class="text-xs text-gray-500">{{ $from }} to {{ $to }}</p>
    </div>
    <form method="GET" action="{{ route('reports.attendance') }}" class="flex items-center gap-2">
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Present</div><div class="text-xl font-bold text-green-600 mt-1">{{ $summary['present'] }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Late</div><div class="text-xl font-bold text-amber-600 mt-1">{{ $summary['late'] }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Absent</div><div class="text-xl font-bold text-red-500 mt-1">{{ $summary['absent'] }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Half Day</div><div class="text-xl font-bold text-sky-600 mt-1">{{ $summary['half_day'] }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">On Leave</div><div class="text-xl font-bold text-purple-600 mt-1">{{ $summary['on_leave'] }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">OT (h)</div><div class="text-xl font-bold text-copper-600 mt-1">{{ number_format($summary['total_ot'] / 60, 1) }}</div></div>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">Per Employee</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium text-right">Present</th>
                    <th class="px-5 py-3 font-medium text-right">Late</th>
                    <th class="px-5 py-3 font-medium text-right">Absent</th>
                    <th class="px-5 py-3 font-medium text-right">OT (min)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byEmployee as $row)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-right text-green-600 font-medium text-xs">{{ $row['present'] }}</td>
                    <td class="px-5 py-3 text-right text-amber-600 font-medium text-xs">{{ $row['late'] }}</td>
                    <td class="px-5 py-3 text-right text-red-500 font-medium text-xs">{{ $row['absent'] }}</td>
                    <td class="px-5 py-3 text-right text-copper-600 font-medium text-xs">{{ $row['ot_minutes'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No data for this period</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

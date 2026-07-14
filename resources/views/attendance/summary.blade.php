@extends('layouts.dashboard')

@section('title', 'Attendance Summary - AYS Call Center')
@section('page_title', 'Attendance · Summary')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Attendance Summary</h2>
        <p class="text-xs text-gray-500">Period summary report</p>
    </div>
    <form method="GET" action="{{ route('attendance.summary') }}" class="flex items-center gap-2">
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Present</div>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $summary['present'] }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Late</div>
        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $summary['late'] }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Absent</div>
        <div class="text-2xl font-bold text-red-600 mt-1">{{ $summary['absent'] }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Total OT</div>
        <div class="text-2xl font-bold text-copper-600 mt-1">{{ number_format($summary['total_ot_minutes'] / 60, 1) }}h</div>
    </div>
</div>

{{-- Per Employee --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Per Employee Breakdown</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Present</th>
                    <th class="px-5 py-3 font-medium">Late</th>
                    <th class="px-5 py-3 font-medium">Absent</th>
                    <th class="px-5 py-3 font-medium">OT (min)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byEmployee as $row)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-green-600 font-medium">{{ $row['present'] }}</td>
                    <td class="px-5 py-3 text-amber-600 font-medium">{{ $row['late'] }}</td>
                    <td class="px-5 py-3 text-red-600 font-medium">{{ $row['absent'] }}</td>
                    <td class="px-5 py-3 text-copper-600 font-medium">{{ $row['ot_minutes'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No data for this period</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

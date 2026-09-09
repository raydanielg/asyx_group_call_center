@extends('layouts.dashboard')

@section('title', 'Missing Attendance - AYS Call Center')
@section('page_title', 'Attendance · Missing')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Missing Attendance</h2>
        <p class="text-xs text-gray-500">Employees without attendance records for the selected date</p>
    </div>
    <form method="GET" action="{{ route('attendance.missing') }}" class="flex flex-wrap items-center gap-2">
        <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="Filter by date">
        <select name="department_id" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="Filter by department">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
            <option value="{{ $dept->id }}" @selected($departmentId == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Apply</button>
        @if($date !== now()->toDateString() || $departmentId)
        <a href="{{ route('attendance.missing') }}" class="px-3 py-2 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">Reset</a>
        @endif
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Total Active</div>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalActive }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Recorded</div>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $recorded }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Missing</div>
        <div class="text-2xl font-bold text-red-500 mt-1">{{ $missing->count() }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-[10px] font-medium text-gray-500 uppercase">Completion</div>
        <div class="text-2xl font-bold text-navy-600 mt-1">{{ $totalActive > 0 ? round(($recorded / $totalActive) * 100) : 0 }}%</div>
    </div>
</div>

{{-- Progress Bar --}}
@if($totalActive > 0)
<div class="bg-white rounded-xl border p-4 mb-4">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-medium text-gray-600">Attendance Completion</span>
        <span class="text-xs font-bold text-navy-600">{{ round(($recorded / $totalActive) * 100) }}%</span>
    </div>
    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all duration-500" style="width: {{ round(($recorded / $totalActive) * 100) }}%"></div>
    </div>
</div>
@endif

{{-- Missing Employees Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Missing Employees ({{ $missing->count() }})</h3>
        @if($missing->count() > 0)
        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</span>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">#</th>
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Code</th>
                    <th class="px-5 py-3 font-medium">Department</th>
                    <th class="px-5 py-3 font-medium">Position</th>
                    <th class="px-5 py-3 font-medium">Shift</th>
                    <th class="px-5 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missing as $i => $emp)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 text-gray-400 font-medium text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-xs">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $emp->phone ?? 'No phone' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs font-mono">{{ $emp->employee_code ?? '—' }}</td>
                    <td class="px-5 py-3 text-xs">
                        @if($emp->department)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-navy-50 text-navy-700 border border-navy-100">{{ $emp->department->name }}</span>
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $emp->position?->title ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-xs">
                        @php $shift = $emp->shiftAssignments->first()?->shift; @endphp
                        @if($shift)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-copper-50 text-copper-700 border border-copper-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $shift->name }}
                        </span>
                        @else
                        <span class="text-gray-400 text-[10px]">No shift</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <button type="button" onclick="openMarkAttendance({{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', '{{ $date }}')" class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-medium text-navy-600 bg-navy-50 hover:bg-navy-100 rounded-xl border border-navy-100 transition-colors" aria-label="Mark attendance for {{ $emp->first_name }} {{ $emp->last_name }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Mark
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center">
                                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">All caught up!</p>
                                <p class="text-xs text-gray-500 mt-1">Every active employee has an attendance record for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missing->count() > 0)
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between">
        <span class="text-xs text-gray-500">Showing {{ $missing->count() }} of {{ $totalActive }} active employees</span>
        <a href="{{ route('attendance.index', ['date' => $date, 'department_id' => $departmentId]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Daily Grid
        </a>
    </div>
    @endif
</div>

@endsection

@extends('layouts.dashboard')

@section('title', 'Attendance - AYS Call Center')
@section('page_title', 'Attendance · Daily Grid')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Daily Attendance Grid</h2>
        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</p>
    </div>
    <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-wrap items-center gap-2">
        <select name="department_id" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="Filter by department">
            <option value="">All Departments</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" aria-label="Filter by date">
    </form>
</div>

{{-- KPI Cards — soft, no borders, rounded --}}
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mb-5">
    <div class="bg-green-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-[9px] font-medium text-green-700 uppercase">Present</span>
        </div>
        <div class="text-xl font-bold text-green-700">{{ $stats['present'] }}</div>
    </div>
    <div class="bg-amber-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-amber-700 uppercase">Late</span>
        </div>
        <div class="text-xl font-bold text-amber-700">{{ $stats['late'] }}</div>
    </div>
    <div class="bg-red-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <span class="text-[9px] font-medium text-red-700 uppercase">Absent</span>
        </div>
        <div class="text-xl font-bold text-red-700">{{ $stats['absent'] }}</div>
    </div>
    <div class="bg-sky-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-sky-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-sky-700 uppercase">Half Day</span>
        </div>
        <div class="text-xl font-bold text-sky-700">{{ $stats['half_day'] }}</div>
    </div>
    <div class="bg-purple-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <span class="text-[9px] font-medium text-purple-700 uppercase">On Leave</span>
        </div>
        <div class="text-xl font-bold text-purple-700">{{ $stats['on_leave'] }}</div>
    </div>
    <div class="bg-gray-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-gray-600 uppercase">Missing</span>
        </div>
        <div class="text-xl font-bold text-gray-700">{{ $stats['missing'] }}</div>
    </div>
    <div class="bg-copper-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-copper-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-copper-700 uppercase">OT (h)</span>
        </div>
        <div class="text-xl font-bold text-copper-700">{{ number_format($stats['ot_minutes'] / 60, 1) }}</div>
    </div>
</div>

{{-- Attendance Grid Table --}}
<div class="bg-white rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Dept</th>
                    <th class="px-5 py-3 font-medium">Shift</th>
                    <th class="px-5 py-3 font-medium">Check In</th>
                    <th class="px-5 py-3 font-medium">Check Out</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">OT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php $rec = $records->get($emp->id); @endphp
                <tr class="border-t border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                {{ strtoupper(substr($emp->first_name ?? 'A', 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-xs">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $emp->employee_code ?? '—' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-xs">
                        @if($emp->department)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-navy-50 text-navy-700">{{ $emp->department->name }}</span>
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs">
                        @php $shift = $emp->shiftAssignments->first()?->shift; @endphp
                        @if($shift)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-copper-50 text-copper-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $shift->name }}
                        </span>
                        @else
                        <span class="text-gray-400 text-[10px]">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs font-mono">{{ $rec?->check_in?->format('H:i') ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs font-mono">{{ $rec?->check_out?->format('H:i') ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if($rec)
                            @php $stColors = ['present'=>'green','late'=>'amber','absent'=>'red','half_day'=>'sky','on_leave'=>'purple','off'=>'gray','holiday'=>'gray']; @endphp
                            @php $color = $stColors[$rec->status] ?? 'gray'; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst(str_replace('_',' ', $rec->status)) }}</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-400">Not marked</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        @if($rec && $rec->overtime_minutes > 0)
                            <span class="inline-flex items-center gap-1.5">
                                <span class="text-xs font-medium text-copper-600">{{ $rec->overtime_minutes }}m</span>
                                @if(!$rec->overtime_approved)
                                <form method="POST" action="{{ route('attendance.approve-overtime', $rec) }}" class="inline" data-ajax data-confirm="Approve this overtime?">
                                    @csrf
                                    <button type="submit" class="text-[9px] text-navy-600 hover:text-navy-700 font-medium">Approve</button>
                                </form>
                                @else
                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-green-100">
                                    <svg class="w-2.5 h-2.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">No active employees</p>
                                <p class="text-xs text-gray-500 mt-1">No employees found for this filter</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->count() > 0)
    <div class="px-5 py-3 border-t border-gray-50 bg-gray-50/30 flex items-center justify-between">
        <span class="text-xs text-gray-500">{{ $employees->count() }} employees · {{ $stats['missing'] }} not marked</span>
        @if($stats['missing'] > 0)
        <a href="{{ route('attendance.missing', ['date' => $date, 'department_id' => request('department_id')]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-navy-600 bg-navy-50 hover:bg-navy-100 rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            View Missing ({{ $stats['missing'] }})
        </a>
        @endif
    </div>
    @endif
</div>

@endsection

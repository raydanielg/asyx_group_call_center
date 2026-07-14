@extends('layouts.dashboard')

@section('title', 'Schedule Planner - AYS Call Center')
@section('page_title', 'Shifts · Planner')

@section('content')

@php
    $today = now()->toDateString();
    $totalSlots = $employees->count() * 7;
    $assignedCount = $assignments->count();
    $unassignedCount = $totalSlots - $assignedCount;
    $coverage = $totalSlots > 0 ? round(($assignedCount / $totalSlots) * 100) : 0;
@endphp

{{-- Header with Week Navigation --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Schedule Planner</h2>
        <p class="text-xs text-gray-500">Week of {{ $start->format('M d, Y') }} — {{ $start->copy()->addDays(6)->format('M d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('shifts.planner', ['week' => $start->copy()->subWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Prev
        </a>
        <a href="{{ route('shifts.planner') }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Today</a>
        <a href="{{ route('shifts.planner', ['week' => $start->copy()->addWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1">
            Next
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-navy-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Employees</p>
                <p class="text-sm font-bold text-gray-900">{{ $employees->count() }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Assigned</p>
                <p class="text-sm font-bold text-gray-900">{{ $assignedCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Unassigned</p>
                <p class="text-sm font-bold text-gray-900">{{ $unassignedCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-copper-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Coverage</p>
                <p class="text-sm font-bold text-gray-900">{{ $coverage }}%</p>
            </div>
        </div>
    </div>
</div>

{{-- Shift Legend --}}
@if($shifts->isNotEmpty())
<div class="bg-white rounded-xl border p-3 mb-4">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Shifts:</span>
        @foreach($shifts as $s)
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded" style="background-color: {{ $s->color }}"></span>
            <span class="text-xs text-gray-700 font-medium">{{ $s->name }}</span>
            <span class="text-[10px] text-gray-400">{{ $s->start_time }}–{{ $s->end_time }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Planner Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="text-left text-gray-500 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-3 py-3 font-medium sticky left-0 bg-gray-50/50 z-10 min-w-[140px]">Employee</th>
                    @foreach($days as $day)
                    <th class="px-2 py-3 font-medium text-center min-w-[110px]">
                        <div class="{{ $day->isWeekend() ? 'text-copper-600' : 'text-gray-700' }}">{{ $day->format('D') }}</div>
                        <div class="text-[10px] {{ $day->toDateString() === $today ? 'text-navy-600 font-bold' : 'text-gray-400' }}">{{ $day->format('M d') }}</div>
                        @if($day->toDateString() === $today)
                        <div class="text-[8px] text-navy-500 font-bold uppercase mt-0.5">Today</div>
                        @endif
                    </th>
                    @endforeach
                    <th class="px-3 py-3 font-medium text-center min-w-[80px]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php $empAssigned = 0; @endphp
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-3 py-2.5 font-medium text-gray-900 sticky left-0 bg-white z-10">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[9px] shrink-0">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}</div>
                            <div class="min-w-0">
                                <div class="truncate max-w-[100px] text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div class="text-[9px] text-gray-400 truncate max-w-[100px]">{{ $emp->employee_code ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach($days as $day)
                    @php
                        $dateStr = $day->toDateString();
                        $assignment = $assignments->get($emp->id . '-' . $dateStr);
                        if ($assignment) $empAssigned++;
                    @endphp
                    <td class="px-1.5 py-2.5 text-center">
                        <div class="relative inline-block shift-cell" data-employee="{{ $emp->id }}" data-date="{{ $dateStr }}">
                            @if($assignment)
                                <button type="button"
                                    onclick="toggleShiftMenu({{ $emp->id }}, '{{ $dateStr }}')"
                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[9px] font-semibold text-white transition-all hover:opacity-80 hover:scale-105"
                                    style="background-color: {{ $assignment->shift?->color ?? '#0D3E63' }}"
                                    title="{{ $assignment->shift?->name ?? 'Shift' }} · {{ $assignment->shift?->start_time ?? '' }}–{{ $assignment->shift?->end_time ?? '' }}">
                                    {{ $assignment->shift?->name ?? 'Shift' }}
                                </button>
                            @else
                                <button type="button"
                                    onclick="toggleShiftMenu({{ $emp->id }}, '{{ $dateStr }}')"
                                    class="inline-flex items-center justify-center w-full px-2 py-1 rounded-md text-[9px] text-gray-300 border border-dashed border-gray-200 hover:border-navy-300 hover:text-navy-500 hover:bg-navy-50/30 transition-all"
                                    title="Assign shift">
                                    +
                                </button>
                            @endif
                        </div>
                    </td>
                    @endforeach
                    <td class="px-2 py-2.5 text-center">
                        <div class="flex items-center justify-center gap-1">
                            @php $hasPrevWeek = isset($prevAssignments[$emp->id]) && $prevAssignments[$emp->id]->isNotEmpty(); @endphp
                            @if($hasPrevWeek)
                            <button type="button"
                                onclick="copyPrevWeek({{ $emp->id }})"
                                class="p-1.5 rounded-lg text-gray-500 hover:bg-navy-50 hover:text-navy-600 transition-colors"
                                title="Copy from previous week">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2"/></svg>
                            </button>
                            @endif
                            <button type="button"
                                onclick="clearWeek({{ $emp->id }})"
                                class="p-1.5 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                title="Clear all shifts this week">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-3 py-10 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="text-sm font-medium">No active employees</p>
                    <p class="text-xs mt-1">Add employees to start scheduling shifts.</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- No Shifts Warning --}}
@if($shifts->isEmpty())
<div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-sm font-medium text-amber-800">No shifts defined yet</p>
            <p class="text-xs text-amber-700 mt-0.5">You need to create shifts first before assigning them to employees. <a href="{{ route('shifts.index') }}" class="font-semibold underline">Go to Shift Catalog →</a></p>
        </div>
    </div>
</div>
@endif

{{-- Shift Selector Dropdown (floating) --}}
<div id="shiftMenu" class="hidden fixed z-50 bg-white rounded-lg shadow-xl border border-gray-200 p-1.5 min-w-[160px]" style="top:0;left:0;">
    <div class="text-[9px] font-semibold text-gray-400 uppercase tracking-wide px-2 py-1">Assign Shift</div>
    @foreach($shifts as $s)
    <button type="button"
        onclick="assignShift({{ $s->id }})"
        class="w-full flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-gray-50 transition-colors text-left">
        <span class="w-3 h-3 rounded shrink-0" style="background-color: {{ $s->color }}"></span>
        <div class="min-w-0">
            <div class="text-xs font-medium text-gray-900 truncate">{{ $s->name }}</div>
            <div class="text-[9px] text-gray-400">{{ $s->start_time }}–{{ $s->end_time }}</div>
        </div>
    </button>
    @endforeach
    <div class="border-t border-gray-100 my-1"></div>
    <button type="button"
        onclick="unassignShift()"
        class="w-full flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-red-50 transition-colors text-left"
        id="shiftMenuRemove" style="display:none;">
        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-xs font-medium text-red-600">Remove Shift</span>
    </button>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const assignUrl = '{{ route('shifts.assign') }}';
    const unassignUrl = '{{ route('shifts.unassign') }}';
    const bulkAssignUrl = '{{ route('shifts.bulk-assign') }}';
    const copyWeekUrl = '{{ route('shifts.copy-week') }}';
    const clearWeekUrl = '{{ route('shifts.clear-week') }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    const dayStrings = @json($dayStrings);
    const prevDayStrings = @json($prevDays);

    let currentEmp = null;
    let currentDate = null;
    const shiftMenu = document.getElementById('shiftMenu');

    window.toggleShiftMenu = function(empId, dateStr) {
        currentEmp = empId;
        currentDate = dateStr;

        // Check if there's an existing assignment
        const removeBtn = document.getElementById('shiftMenuRemove');
        const cell = document.querySelector(`.shift-cell[data-employee="${empId}"][data-date="${dateStr}"]`);
        const hasShift = cell && cell.querySelector('button[style*="background-color"]');
        removeBtn.style.display = hasShift ? 'flex' : 'none';

        // Position the menu near the clicked cell
        const btn = event.target.closest('button');
        if (btn) {
            const rect = btn.getBoundingClientRect();
            shiftMenu.style.top = (rect.bottom + window.scrollY + 4) + 'px';
            shiftMenu.style.left = (rect.left + window.scrollX) + 'px';
        }

        shiftMenu.classList.toggle('hidden');

        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', closeShiftMenu, { once: true });
        }, 10);
    };

    function closeShiftMenu(e) {
        if (!shiftMenu.contains(e.target)) {
            shiftMenu.classList.add('hidden');
        }
    }

    window.assignShift = function(shiftId) {
        shiftMenu.classList.add('hidden');
        ajaxRequest(assignUrl, 'POST', { employee_id: currentEmp, shift_id: shiftId, date: currentDate }, { reload: true });
    };

    window.unassignShift = function() {
        shiftMenu.classList.add('hidden');
        ajaxRequest(unassignUrl, 'POST', { employee_id: currentEmp, date: currentDate }, { reload: true });
    };

    window.copyPrevWeek = function(empId) {
        confirmAction({
            title: 'Copy shifts from previous week?',
            text: 'This will overwrite any existing shifts for this week.',
            confirmText: 'Yes, copy',
            confirmClass: 'bg-navy-500 hover:bg-navy-600 text-white',
            icon: 'question',
        }).then(result => {
            if (result.isConfirmed) {
                ajaxRequest(copyWeekUrl, 'POST', {
                    employee_id: empId,
                    source_dates: prevDayStrings,
                    target_dates: dayStrings,
                }, { reload: true });
            }
        });
    };

    window.clearWeek = function(empId) {
        confirmAction({
            title: 'Clear all shifts for this week?',
            text: 'This will remove all shift assignments for this employee this week.',
            confirmText: 'Yes, clear all',
            confirmClass: 'bg-red-500 hover:bg-red-600 text-white',
        }).then(result => {
            if (result.isConfirmed) {
                ajaxRequest(clearWeekUrl, 'POST', {
                    employee_id: empId,
                    dates: dayStrings,
                }, { reload: true });
            }
        });
    };

    // Close menu on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') shiftMenu.classList.add('hidden');
    });
})();
</script>
@endendpush

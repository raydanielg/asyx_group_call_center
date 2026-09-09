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
    $dayStringsJson = json_encode($dayStrings);
    $prevDaysJson = json_encode($prevDays);
    $shiftsJson = $shifts->map(fn($s) => [
        'id' => $s->id,
        'name' => $s->name,
        'code' => $s->code,
        'color' => $s->color ?? '#0D3E63',
        'start_time' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
        'end_time' => \Carbon\Carbon::parse($s->end_time)->format('H:i'),
    ])->toJson();
@endphp

{{-- Header with Week Navigation --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Schedule Planner</h2>
        <p class="text-xs text-gray-500">Week of {{ $start->format('M d, Y') }} — {{ $start->copy()->addDays(6)->format('M d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('shifts.planner', ['week' => $start->copy()->subWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs font-medium border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Prev
        </a>
        <a href="{{ route('shifts.planner') }}" class="px-3 py-2 text-xs font-medium border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">Today</a>
        <a href="{{ route('shifts.planner', ['week' => $start->copy()->addWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs font-medium border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors inline-flex items-center gap-1">
            Next
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

{{-- KPI Cards — soft, no borders, rounded --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-navy-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-navy-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-navy-700 uppercase">Employees</span>
        </div>
        <div class="text-xl font-bold text-navy-700">{{ $employees->count() }}</div>
    </div>
    <div class="bg-green-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-[9px] font-medium text-green-700 uppercase">Assigned</span>
        </div>
        <div class="text-xl font-bold text-green-700">{{ $assignedCount }}</div>
    </div>
    <div class="bg-amber-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-amber-700 uppercase">Unassigned</span>
        </div>
        <div class="text-xl font-bold text-amber-700">{{ $unassignedCount }}</div>
    </div>
    <div class="bg-copper-50 rounded-2xl p-3.5">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-6 h-6 rounded-full bg-copper-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-[9px] font-medium text-copper-700 uppercase">Coverage</span>
        </div>
        <div class="text-xl font-bold text-copper-700">{{ $coverage }}%</div>
    </div>
</div>

{{-- Coverage Bar --}}
<div class="bg-white rounded-2xl p-4 mb-5">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-medium text-gray-600">Weekly Coverage</span>
        <span class="text-xs font-bold text-gray-900">{{ $coverage }}%</span>
    </div>
    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $coverage }}%; background: linear-gradient(90deg, #0D3E63, #A56035);"></div>
    </div>
</div>

{{-- Shift Legend --}}
@if($shifts->isNotEmpty())
<div class="bg-white rounded-2xl p-3 mb-5">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-[10px] font-semibold text-gray-400 uppercase">Shifts:</span>
        @foreach($shifts as $s)
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-md" style="background-color: {{ $s->color }}"></span>
            <span class="text-xs text-gray-700 font-medium">{{ $s->name }}</span>
            <span class="text-[10px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Planner Table --}}
<div class="bg-white rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="text-left text-gray-500 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium sticky left-0 bg-gray-50/50 z-10 min-w-[140px]">Employee</th>
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
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-4 py-2.5 font-medium text-gray-900 sticky left-0 bg-white z-10">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[9px] shrink-0">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}</div>
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
                    @endphp
                    <td class="px-1.5 py-2.5 text-center">
                        <div class="relative inline-block shift-cell" data-employee="{{ $emp->id }}" data-date="{{ $dateStr }}">
                            @if($assignment)
                                <button type="button"
                                    onclick="toggleShiftMenu(event, {{ $emp->id }}, '{{ $dateStr }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[9px] font-semibold text-white transition-all hover:opacity-80 hover:scale-105 shadow-sm"
                                    style="background-color: {{ $assignment->shift?->color ?? '#0D3E63' }}"
                                    data-shift-id="{{ $assignment->shift_id }}"
                                    title="{{ $assignment->shift?->name ?? 'Shift' }} · {{ \Carbon\Carbon::parse($assignment->shift?->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($assignment->shift?->end_time)->format('H:i') }}">
                                    {{ $assignment->shift?->code ?? $assignment->shift?->name ?? 'Shift' }}
                                </button>
                            @else
                                <button type="button"
                                    onclick="toggleShiftMenu(event, {{ $emp->id }}, '{{ $dateStr }}')"
                                    class="inline-flex items-center justify-center w-full px-2.5 py-1.5 rounded-xl text-[10px] text-gray-300 border border-dashed border-gray-200 hover:border-navy-300 hover:text-navy-500 hover:bg-navy-50/30 transition-all"
                                    title="Assign shift">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
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
                                class="p-1.5 rounded-xl text-gray-500 hover:bg-navy-50 hover:text-navy-600 transition-colors"
                                title="Copy from previous week">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2"/></svg>
                            </button>
                            @endif
                            <button type="button"
                                onclick="clearWeek({{ $emp->id }})"
                                class="p-1.5 rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                title="Clear all shifts this week">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">No active employees</p>
                            <p class="text-xs text-gray-500 mt-1">Add employees to start scheduling shifts.</p>
                        </div>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- No Shifts Warning --}}
@if($shifts->isEmpty())
<div class="mt-4 bg-amber-50 rounded-2xl p-4">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-sm font-medium text-amber-800">No shifts defined yet</p>
            <p class="text-xs text-amber-700 mt-0.5">You need to create shifts first before assigning them to employees. <a href="{{ route('shifts.index') }}" class="font-semibold underline">Go to Shift Catalog &rarr;</a></p>
        </div>
    </div>
</div>
@endif

{{-- Shift Selector Dropdown (floating) --}}
<div id="shiftMenu" class="hidden fixed z-50 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 min-w-[180px]" style="top:0;left:0;">
    <div class="text-[9px] font-semibold text-gray-400 uppercase tracking-wide px-2 py-1">Assign Shift</div>
    @foreach($shifts as $s)
    <button type="button"
        onclick="assignShift({{ $s->id }}, '{{ $s->name }}', '{{ $s->code }}', '{{ $s->color ?? '#0D3E63' }}')"
        class="w-full flex items-center gap-2 px-2 py-2 rounded-xl hover:bg-gray-50 transition-colors text-left">
        <span class="w-3 h-3 rounded-md shrink-0" style="background-color: {{ $s->color }}"></span>
        <div class="min-w-0">
            <div class="text-xs font-medium text-gray-900 truncate">{{ $s->name }}</div>
            <div class="text-[9px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</div>
        </div>
    </button>
    @endforeach
    <div class="border-t border-gray-100 my-1"></div>
    <button type="button"
        onclick="unassignShift()"
        class="w-full flex items-center gap-2 px-2 py-2 rounded-xl hover:bg-red-50 transition-colors text-left"
        id="shiftMenuRemove" style="display:none;">
        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-xs font-medium text-red-600">Remove Shift</span>
    </button>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const assignUrl = '{{ route('shifts.assign') }}';
    const unassignUrl = '{{ route('shifts.unassign') }}';
    const copyWeekUrl = '{{ route('shifts.copy-week') }}';
    const clearWeekUrl = '{{ route('shifts.clear-week') }}';

    const dayStrings = {!! $dayStringsJson !!};
    const prevDayStrings = {!! $prevDaysJson !!};
    const shiftsData = {!! $shiftsJson !!};

    let currentEmp = null;
    let currentDate = null;
    const shiftMenu = document.getElementById('shiftMenu');

    window.toggleShiftMenu = function(e, empId, dateStr) {
        e.stopPropagation();
        currentEmp = empId;
        currentDate = dateStr;

        const cell = document.querySelector(`.shift-cell[data-employee="${empId}"][data-date="${dateStr}"]`);
        const hasShift = cell && cell.querySelector('button[data-shift-id]');
        document.getElementById('shiftMenuRemove').style.display = hasShift ? 'flex' : 'none';

        const btn = e.target.closest('button');
        if (btn) {
            const rect = btn.getBoundingClientRect();
            shiftMenu.style.top = (rect.bottom + window.scrollY + 4) + 'px';
            shiftMenu.style.left = (rect.left + window.scrollX) + 'px';
        }

        shiftMenu.classList.remove('hidden');
        setTimeout(() => { document.addEventListener('click', closeShiftMenu, { once: true }); }, 10);
    };

    function closeShiftMenu(e) {
        if (!shiftMenu.contains(e.target)) shiftMenu.classList.add('hidden');
    }

    function updateCellUI(shiftId, shiftName, shiftCode, shiftColor) {
        const cell = document.querySelector(`.shift-cell[data-employee="${currentEmp}"][data-date="${currentDate}"]`);
        if (!cell) return;
        if (shiftId) {
            cell.innerHTML = `<button type="button" onclick="toggleShiftMenu(event, ${currentEmp}, '${currentDate}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[9px] font-semibold text-white transition-all hover:opacity-80 hover:scale-105 shadow-sm" style="background-color: ${shiftColor}" data-shift-id="${shiftId}" title="${shiftName}">${shiftCode}</button>`;
        } else {
            cell.innerHTML = `<button type="button" onclick="toggleShiftMenu(event, ${currentEmp}, '${currentDate}')" class="inline-flex items-center justify-center w-full px-2.5 py-1.5 rounded-xl text-[10px] text-gray-300 border border-dashed border-gray-200 hover:border-navy-300 hover:text-navy-500 hover:bg-navy-50/30 transition-all"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></button>`;
        }
    }

    function updateKPIs(deltaAssigned) {
        const assignedEl = document.querySelectorAll('.text-xl.font-bold.text-green-700')[0];
        const unassignedEl = document.querySelectorAll('.text-xl.font-bold.text-amber-700')[0];
        const coverageEl = document.querySelectorAll('.text-xl.font-bold.text-copper-700')[0];
        const barEl = document.querySelector('.h-full.rounded-full.transition-all');

        const total = {{ $totalSlots }};
        let assigned = parseInt(assignedEl?.textContent || '0') + deltaAssigned;
        let unassigned = total - assigned;
        let coverage = total > 0 ? Math.round((assigned / total) * 100) : 0;

        if (assignedEl) assignedEl.textContent = assigned;
        if (unassignedEl) unassignedEl.textContent = unassigned;
        if (coverageEl) coverageEl.textContent = coverage + '%';
        if (barEl) barEl.style.width = coverage + '%';
    }

    window.assignShift = function(shiftId, shiftName, shiftCode, shiftColor) {
        shiftMenu.classList.add('hidden');
        const cell = document.querySelector(`.shift-cell[data-employee="${currentEmp}"][data-date="${currentDate}"]`);
        const wasUnassigned = !cell?.querySelector('button[data-shift-id]');

        ajaxRequest(assignUrl, 'POST', {
            employee_id: currentEmp,
            shift_id: shiftId,
            date: currentDate,
        }, { reload: false, onSuccess: () => {
            updateCellUI(shiftId, shiftName, shiftCode, shiftColor);
            if (wasUnassigned) updateKPIs(1);
        }});
    };

    window.unassignShift = function() {
        shiftMenu.classList.add('hidden');
        const cell = document.querySelector(`.shift-cell[data-employee="${currentEmp}"][data-date="${currentDate}"]`);
        const wasAssigned = !!cell?.querySelector('button[data-shift-id]');

        ajaxRequest(unassignUrl, 'POST', {
            employee_id: currentEmp,
            date: currentDate,
        }, { reload: false, onSuccess: () => {
            updateCellUI(null, null, null, null);
            if (wasAssigned) updateKPIs(-1);
        }});
    };

    window.copyPrevWeek = function(empId) {
        confirmAction({
            title: 'Copy shifts from previous week?',
            text: 'This will overwrite any existing shifts for this week.',
            confirmText: 'Yes, copy',
            confirmClass: 'bg-navy-50 hover:bg-navy-100 text-navy-600 border border-navy-200',
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
            confirmClass: 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-200',
        }).then(result => {
            if (result.isConfirmed) {
                ajaxRequest(clearWeekUrl, 'POST', {
                    employee_id: empId,
                    dates: dayStrings,
                }, { reload: true });
            }
        });
    };

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') shiftMenu.classList.add('hidden');
    });
})();
</script>
@endpush

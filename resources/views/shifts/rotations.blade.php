@extends('layouts.dashboard')

@section('title', 'Shift Rotations - AYS Call Center')
@section('page_title', 'Shifts · Rotations')

@section('content')

@php
    $shiftsJson = $shifts->map(fn($s) => [
        'id' => $s->id,
        'name' => $s->name,
        'code' => $s->code,
        'color' => $s->color ?? '#0D3E63',
    ])->toJson();
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Shift Rotations</h2>
        <p class="text-xs text-gray-500">Define recurring rotation patterns for teams and departments</p>
    </div>
    <button onclick="openCreateRotation()" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Rotation
    </button>
</div>

@if($rotations->isNotEmpty())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($rotations as $r)
    <div class="bg-white rounded-2xl p-5 hover:shadow-md transition-shadow" id="rotation-card-{{ $r->id }}">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $r->name }}</h3>
                    <span class="text-[10px] text-gray-400">{{ $r->cycle_days }}-day cycle</span>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ ($r->is_active ?? true) ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">{{ ($r->is_active ?? true) ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Applies To</span>
                <span class="font-medium text-gray-700 capitalize">{{ $r->applies_to ?? 'department' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Target</span>
                <span class="font-medium text-gray-700">{{ $r->department?->name ?? $r->team?->name ?? 'All' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Starts On</span>
                <span class="font-medium text-gray-700">{{ $r->starts_on?->format('M d, Y') ?? 'N/A' }}</span>
            </div>
        </div>
        @if($r->pattern && is_array($r->pattern) && count($r->pattern) > 0)
        <div class="mt-3 pt-3 border-t border-gray-50">
            <div class="text-[9px] font-medium text-gray-400 uppercase mb-2">Pattern</div>
            <div class="flex flex-wrap gap-1">
                @foreach($r->pattern as $idx => $shiftId)
                    @php $shift = $shifts->firstWhere('id', $shiftId); @endphp
                    <div class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[8px] font-medium" style="background-color: {{ $shift?->color ?? '#e5e7eb' }}20; color: {{ $shift?->color ?? '#9ca3af' }}">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $shift?->color ?? '#d1d5db' }}"></span>
                        D{{ $idx + 1 }}: {{ $shift?->code ?? $shift?->name ?? 'Off' }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50">
            <form method="POST" action="{{ route('shifts.rotations.destroy', $r) }}" class="flex-1" data-ajax data-confirm="Delete {{ $r->name }}?" data-confirm-text="This rotation will be permanently removed and cannot be undone." data-row-id="rotation-card-{{ $r->id }}">
                @csrf @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <p class="text-sm font-semibold text-gray-900">No rotations defined yet</p>
        <p class="text-xs text-gray-500 mt-1">Click "Add Rotation" to create a recurring shift pattern.</p>
    </div>
    @endforelse
</div>

@if($rotations->hasPages())
<div class="px-5 py-3 mt-4">{{ $rotations->links() }}</div>
@endif
@else
<div class="bg-white rounded-2xl p-12 text-center">
    <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    </div>
    <p class="text-sm font-semibold text-gray-900">No rotations defined yet</p>
    <p class="text-xs text-gray-500 mt-1">Click "Add Rotation" to create a recurring shift pattern.</p>
</div>
@endif

{{-- Drawer --}}
<div id="modal-rot" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-rot-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeRotationDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="rotationDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-rot-title">Add Rotation</h3>
            </div>
            <button type="button" onclick="closeRotationDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-rotation-create" method="POST" action="{{ route('shifts.rotations.store') }}" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-rot" data-reset-on-success="true">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" placeholder="e.g. Weekly Morning Rotation">
            </div>

            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Applies To <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="selectAppliesTo('department')" data-applies="department" class="applies-to-btn px-3 py-2 text-xs font-medium rounded-xl border transition-all">Department</button>
                    <button type="button" onclick="selectAppliesTo('team')" data-applies="team" class="applies-to-btn px-3 py-2 text-xs font-medium rounded-xl border transition-all">Team</button>
                    <button type="button" onclick="selectAppliesTo('employees')" data-applies="employees" class="applies-to-btn px-3 py-2 text-xs font-medium rounded-xl border transition-all">All Staff</button>
                </div>
                <input type="hidden" name="applies_to" id="applies_to_input" value="department">
            </div>

            <div id="dept-team-section" class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Department</label>
                    <select name="department_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        <option value="">All Departments</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Team</label>
                    <select name="team_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        <option value="">All Teams</option>
                        @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Cycle Days <span class="text-red-500">*</span></label>
                    <input type="number" name="cycle_days" id="cycle_days_input" value="7" min="1" max="30" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="starts_on" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Rotation Pattern</label>
                <p class="text-[10px] text-gray-400 mb-2">Select a shift for each day in the cycle. Leave as "Off" for rest days.</p>
                <div id="patternBuilder" class="space-y-1.5 max-h-48 overflow-y-auto pr-1"></div>
                <input type="hidden" name="pattern" id="pattern_input" value="[]">
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeRotationDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Create Rotation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const shiftsData = {!! $shiftsJson !!};
    const drawer = document.getElementById('rotationDrawer');

    function openDrawer() {
        document.getElementById('modal-rot').classList.remove('hidden');
        requestAnimationFrame(() => drawer.classList.remove('translate-x-full'));
    }

    window.closeRotationDrawer = function() {
        drawer.classList.add('translate-x-full');
        setTimeout(() => document.getElementById('modal-rot').classList.add('hidden'), 300);
    };

    window.openCreateRotation = function() {
        document.getElementById('form-rotation-create').reset();
        document.getElementById('cycle_days_input').value = 7;
        selectAppliesTo('department');
        rebuildPattern(7);
        openDrawer();
    };

    window.selectAppliesTo = function(type) {
        document.getElementById('applies_to_input').value = type;
        document.querySelectorAll('.applies-to-btn').forEach(btn => {
            const active = btn.dataset.applies === type;
            btn.classList.toggle('bg-navy-600', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('border-navy-600', active);
            btn.classList.toggle('bg-white', !active);
            btn.classList.toggle('text-gray-600', !active);
            btn.classList.toggle('border-gray-200', !active);
        });
        document.getElementById('dept-team-section').style.display = type === 'employees' ? 'none' : '';
    };

    function rebuildPattern(days) {
        const container = document.getElementById('patternBuilder');
        container.innerHTML = '';
        for (let i = 0; i < days; i++) {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `
                <span class="text-[10px] font-medium text-gray-400 w-6 shrink-0">D${i + 1}</span>
                <select class="pattern-select flex-1 px-2.5 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" data-day="${i}">
                    <option value="">Off / Rest</option>
                    ${shiftsData.map(s => `<option value="${s.id}">${s.name} (${s.code})</option>`).join('')}
                </select>
            `;
            container.appendChild(row);
        }
        container.querySelectorAll('.pattern-select').forEach(sel => {
            sel.addEventListener('change', updatePatternInput);
        });
        updatePatternInput();
    }

    function updatePatternInput() {
        const selects = document.querySelectorAll('.pattern-select');
        const pattern = [];
        selects.forEach(sel => {
            pattern.push(sel.value ? parseInt(sel.value) : null);
        });
        document.getElementById('pattern_input').value = JSON.stringify(pattern);
    }

    document.getElementById('cycle_days_input').addEventListener('input', function() {
        const days = Math.max(1, Math.min(30, parseInt(this.value) || 7));
        rebuildPattern(days);
    });

    rebuildPattern(7);
})();
</script>
@endpush

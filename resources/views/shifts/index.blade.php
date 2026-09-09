@extends('layouts.dashboard')

@section('title', 'Shifts - AYS Call Center')
@section('page_title', 'Shifts · Catalog')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Shift Catalog</h2>
        <p class="text-xs text-gray-500">Define shift patterns for your call center</p>
    </div>
    <button onclick="openCreateShift()" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Shift
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shifts as $shift)
    <div class="bg-white rounded-2xl p-5 hover:shadow-md transition-shadow" id="shift-card-{{ $shift->id }}">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-5 h-5 rounded-lg" style="background-color: {{ $shift->color ?? '#0D3E63' }}"></span>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $shift->name }}</h3>
                    <span class="text-[10px] text-gray-400 font-mono">{{ $shift->code }}</span>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $shift->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">{{ $shift->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-xs">
                <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-gray-400">Start</span>
                <span class="font-medium text-gray-700 ml-auto font-mono">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-gray-400">End</span>
                <span class="font-medium text-gray-700 ml-auto font-mono">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <div class="w-7 h-7 rounded-lg bg-copper-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="text-gray-400">Break</span>
                <span class="font-medium text-gray-700 ml-auto">{{ $shift->break_minutes ?? 0 }} min</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-gray-400">Grace</span>
                <span class="font-medium text-gray-700 ml-auto">{{ $shift->grace_minutes ?? 0 }} min</span>
            </div>
            @if($shift->is_night_shift)
            <div class="flex items-center gap-2 text-xs pt-1">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </div>
                <span class="text-indigo-600 font-medium">Night Shift</span>
            </div>
            @endif
        </div>
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50">
            <button type="button" onclick="openEditShift({{ $shift->id }})" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-navy-600 bg-navy-50 hover:bg-navy-100 rounded-xl transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="POST" action="{{ route('shifts.destroy', $shift) }}" class="inline" data-ajax data-confirm="Delete this shift?" data-confirm-text="This shift will be permanently removed." data-row-id="shift-card-{{ $shift->id }}">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-sm font-semibold text-gray-900">No shifts defined yet</p>
        <p class="text-xs text-gray-500 mt-1">Click "Add Shift" to create your first shift pattern.</p>
    </div>
    @endforelse
</div>

@if($shifts->hasPages())
<div class="px-5 py-3 mt-4">{{ $shifts->links() }}</div>
@endif

{{-- Create / Edit Modal --}}
<div id="modal-shift" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-shift-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeModal('modal-shift')"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="shiftDrawer">
        {{-- Header --}}
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-shift-title">Add Shift</h3>
            </div>
            <button type="button" onclick="closeShiftModal()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Create Form --}}
        <form id="form-shift-create" method="POST" action="{{ route('shifts.store') }}" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-shift" data-reset-on-success="true">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all" placeholder="e.g. Morning Shift">
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" required placeholder="e.g. MOR, AFT, NGT" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all font-mono">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Break (min)</label>
                    <input type="number" name="break_minutes" value="60" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Grace (min)</label>
                    <input type="number" name="grace_minutes" value="10" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Color</label>
                <input type="text" name="color" placeholder="#0D3E63" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all font-mono">
            </div>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_night_shift" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                <span class="text-xs text-gray-600">Night Shift</span>
            </label>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeShiftModal()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Add Shift</button>
            </div>
        </form>

        {{-- Edit Form --}}
        <form id="form-shift-edit" method="POST" class="flex-1 overflow-y-auto px-5 py-4 space-y-4 hidden" data-ajax data-close-modal="modal-shift">
            @csrf @method('PUT')
            <input type="hidden" name="is_active" value="0">
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all font-mono">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Break (min)</label>
                    <input type="number" name="break_minutes" value="60" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Grace (min)</label>
                    <input type="number" name="grace_minutes" value="10" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Color</label>
                <input type="text" name="color" placeholder="#0D3E63" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all font-mono">
            </div>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_night_shift" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                <span class="text-xs text-gray-600">Night Shift</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                <span class="text-xs text-gray-600">Active</span>
            </label>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeShiftModal()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    @php
        $shiftsJson = $shifts->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'code' => $s->code,
            'start_time' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
            'end_time' => \Carbon\Carbon::parse($s->end_time)->format('H:i'),
            'break_minutes' => $s->break_minutes,
            'grace_minutes' => $s->grace_minutes,
            'color' => $s->color,
            'is_night_shift' => (bool) $s->is_night_shift,
            'is_active' => (bool) $s->is_active,
        ])->toJson();
    @endphp
    const shiftsData = {!! $shiftsJson !!};
    const editForm = document.getElementById('form-shift-edit');
    const createForm = document.getElementById('form-shift-create');
    const modalTitle = document.getElementById('modal-shift-title');
    const drawer = document.getElementById('shiftDrawer');

    function openDrawer() {
        document.getElementById('modal-shift').classList.remove('hidden');
        requestAnimationFrame(() => drawer.classList.remove('translate-x-full'));
    }

    window.closeShiftModal = function() {
        drawer.classList.add('translate-x-full');
        setTimeout(() => document.getElementById('modal-shift').classList.add('hidden'), 300);
    };

    window.openCreateShift = function() {
        modalTitle.textContent = 'Add Shift';
        createForm.classList.remove('hidden');
        editForm.classList.add('hidden');
        createForm.reset();
        openDrawer();
    };

    window.openEditShift = function(id) {
        const s = shiftsData.find(x => x.id == id);
        if (!s) return;

        modalTitle.textContent = 'Edit Shift';
        createForm.classList.add('hidden');
        editForm.classList.remove('hidden');
        editForm.action = '{{ route("shifts.update", "__ID__") }}'.replace('__ID__', id);

        editForm.querySelector('[name="name"]').value = s.name;
        editForm.querySelector('[name="code"]').value = s.code;
        editForm.querySelector('[name="start_time"]').value = s.start_time;
        editForm.querySelector('[name="end_time"]').value = s.end_time;
        editForm.querySelector('[name="break_minutes"]').value = s.break_minutes ?? 60;
        editForm.querySelector('[name="grace_minutes"]').value = s.grace_minutes ?? 10;
        editForm.querySelector('[name="color"]').value = s.color ?? '';
        editForm.querySelector('[name="is_night_shift"]').checked = !!s.is_night_shift;
        editForm.querySelector('[name="is_active"]').checked = !!s.is_active;

        openDrawer();
    };
})();
</script>
@endpush

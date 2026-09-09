@extends('layouts.dashboard')

@section('title', 'Shifts - AYS Call Center')
@section('page_title', 'Shifts · Catalog')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Shift Catalog</h2>
        <p class="text-xs text-gray-500">Define shift patterns</p>
    </div>
    <button onclick="openModal('modal-shift')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Shift
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shifts as $shift)
    <div class="bg-white rounded-xl border p-4 hover:shadow-md transition-shadow" id="shift-card-{{ $shift->id }}">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded" style="background-color: {{ $shift->color ?? '#0D3E63' }}"></span>
                <h3 class="text-sm font-semibold text-gray-900">{{ $shift->name }}</h3>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $shift->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $shift->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="text-xs text-gray-500 space-y-1.5">
            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> <span class="text-gray-400">Start:</span> {{ $shift->start_time }}</div>
            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> <span class="text-gray-400">End:</span> {{ $shift->end_time }}</div>
            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> <span class="text-gray-400">Break:</span> {{ $shift->break_minutes ?? 0 }} min</div>
            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> <span class="text-gray-400">Grace:</span> {{ $shift->grace_minutes ?? 0 }} min</div>
            @if($shift->is_night_shift)
            <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg> <span class="text-indigo-500 font-medium">Night Shift</span></div>
            @endif
        </div>
        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50">
            <button type="button" onclick="openEditShift({{ $shift->id }})" class="text-xs font-medium text-navy-600 hover:text-navy-700 inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="POST" action="{{ route('shifts.destroy', $shift) }}" class="inline" data-ajax data-confirm="Delete this shift?" data-row-id="shift-card-{{ $shift->id }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-600 inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border p-10 text-center text-gray-400">
        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-medium">No shifts defined yet</p>
        <p class="text-xs mt-1">Click "Add Shift" to create your first shift pattern.</p>
    </div>
    @endforelse
</div>

<div class="px-5 py-3 mt-4">{{ $shifts->links() }}</div>

<div id="modal-shift" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('modal-shift')"></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl mx-4">
        <div class="flex items-center gap-3 p-5 border-b border-gray-100">
            <div class="w-9 h-9 rounded-lg bg-navy-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900" id="modal-shift-title">Add Shift</h3>
            <button type="button" onclick="closeModal('modal-shift')" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-shift-create" method="POST" action="{{ route('shifts.store') }}" class="p-5 space-y-3" data-ajax data-close-modal="modal-shift" data-reset-on-success="true">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required placeholder="e.g. MOR, AFT, NGT" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Time <span class="text-red-500">*</span></label><input type="time" name="start_time" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">End Time <span class="text-red-500">*</span></label><input type="time" name="end_time" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Break (min)</label><input type="number" name="break_minutes" value="60" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input type="number" name="grace_minutes" value="10" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Color</label><input type="text" name="color" placeholder="#0D3E63" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div class="flex items-end gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_night_shift" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                        Night Shift
                    </label>
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Add Shift</button>
                <button type="button" onclick="closeModal('modal-shift')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </form>
        <form id="form-shift-edit" method="POST" class="p-5 space-y-3 hidden" data-ajax data-close-modal="modal-shift">
            @csrf @method('PUT')
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Time <span class="text-red-500">*</span></label><input type="time" name="start_time" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">End Time <span class="text-red-500">*</span></label><input type="time" name="end_time" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Break (min)</label><input type="number" name="break_minutes" value="60" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input type="number" name="grace_minutes" value="10" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Color</label><input type="text" name="color" placeholder="#0D3E63" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div class="flex items-end gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_night_shift" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                        Night Shift
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-1">
                <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    Active
                </label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Save Changes</button>
                <button type="button" onclick="closeModal('modal-shift')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
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
            'start_time' => $s->start_time,
            'end_time' => $s->end_time,
            'break_minutes' => $s->break_minutes,
            'grace_minutes' => $s->grace_minutes,
            'color' => $s->color,
            'is_night_shift' => $s->is_night_shift,
            'is_active' => $s->is_active,
        ])->toJson();
    @endphp
    const shiftsData = {!! $shiftsJson !!};
    const editForm = document.getElementById('form-shift-edit');
    const createForm = document.getElementById('form-shift-create');
    const modalTitle = document.getElementById('modal-shift-title');

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
        editForm.querySelector('[name="break_minutes"]').value = s.break_minutes;
        editForm.querySelector('[name="grace_minutes"]').value = s.grace_minutes;
        editForm.querySelector('[name="color"]').value = s.color;
        editForm.querySelector('[name="is_night_shift"]').checked = !!s.is_night_shift;
        editForm.querySelector('[name="is_active"]').checked = !!s.is_active;

        openModal('modal-shift');
    };

    // Reset to create mode when opening via Add button
    document.querySelector('button[onclick="openModal(\'modal-shift\')"]')?.addEventListener('click', () => {
        modalTitle.textContent = 'Add Shift';
        createForm.classList.remove('hidden');
        editForm.classList.add('hidden');
    });
})();
</script>
@endendpush

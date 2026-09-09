@extends('layouts.dashboard')

@section('title', 'Leave Types - AYS Call Center')
@section('page_title', 'Leave · Types')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Types</h2>
        <p class="text-xs text-gray-500">Define leave categories and entitlements</p>
    </div>
    <button onclick="openCreateLt()" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Leave Type
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($types as $t)
    <div class="bg-white rounded-2xl p-5 hover:shadow-md transition-shadow" id="lt-card-{{ $t->id }}">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $t->name }}</h3>
                    <span class="text-[10px] text-gray-400 font-mono">{{ $t->code }}</span>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $t->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs mb-1">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Days/Year</span>
                <span class="font-medium text-gray-700">{{ $t->days_per_year }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Gender</span>
                <span class="font-medium text-gray-700 capitalize">{{ $t->gender_restriction ?? 'any' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Paid</span>
                @if($t->is_paid)
                <span class="inline-flex items-center gap-0.5 text-green-600 font-medium"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Yes</span>
                @else
                <span class="text-gray-400">No</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Carry Fwd</span>
                @if($t->carry_forward)
                <span class="inline-flex items-center gap-0.5 text-green-600 font-medium"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Yes</span>
                @else
                <span class="text-gray-400">No</span>
                @endif
            </div>
        </div>
        @if($t->requires_attachment)
        <div class="inline-flex items-center gap-1 mt-1 text-[10px] text-amber-600 font-medium">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            Requires Attachment
        </div>
        @endif
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50">
            <button type="button" onclick="openEditLt({{ $t->id }})" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="POST" action="{{ route('leave.types.destroy', $t) }}" class="inline" data-ajax data-confirm="Delete {{ $t->name }}?" data-confirm-text="This leave type will be permanently removed and cannot be undone." data-row-id="lt-card-{{ $t->id }}">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <p class="text-sm font-semibold text-gray-900">No leave types defined yet</p>
        <p class="text-xs text-gray-500 mt-1">Click "Add Leave Type" to create your first leave category.</p>
    </div>
    @endforelse
</div>

@if($types->hasPages())
<div class="px-5 py-3 mt-4">{{ $types->links() }}</div>
@endif

{{-- Drawer --}}
<div id="modal-lt" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-lt-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeLtDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="ltDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-lt-title">Add Leave Type</h3>
            </div>
            <button type="button" onclick="closeLtDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Create Form --}}
        <form id="form-lt-create" method="POST" action="{{ route('leave.types.store') }}" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-lt" data-reset-on-success="true">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Annual Leave, Sick Leave" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" required placeholder="e.g. AL, SL, ML" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all font-mono">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Days/Year <span class="text-red-500">*</span></label>
                    <input type="number" step="0.5" name="days_per_year" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Gender Restriction</label>
                    <select name="gender_restriction" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        <option value="any">Any</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-2.5">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_paid" value="1" checked class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Paid Leave</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="carry_forward" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Carry Forward</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="requires_attachment" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Requires Attachment</span>
                </label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeLtDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Add Leave Type</button>
            </div>
        </form>

        {{-- Edit Form --}}
        <form id="form-lt-edit" method="POST" class="flex-1 overflow-y-auto px-5 py-4 space-y-4 hidden" data-ajax data-close-modal="modal-lt">
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
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Days/Year <span class="text-red-500">*</span></label>
                    <input type="number" step="0.5" name="days_per_year" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Gender Restriction</label>
                    <select name="gender_restriction" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        <option value="any">Any</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-2.5">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_paid" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Paid Leave</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="carry_forward" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Carry Forward</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="requires_attachment" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Requires Attachment</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-300">
                    <span class="text-xs text-gray-600">Active</span>
                </label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeLtDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
@php
    $typesJson = $types->map(fn($t) => [
        'id' => $t->id,
        'name' => $t->name,
        'code' => $t->code,
        'days_per_year' => (string) $t->days_per_year,
        'is_paid' => (bool) $t->is_paid,
        'carry_forward' => (bool) $t->carry_forward,
        'requires_attachment' => (bool) $t->requires_attachment,
        'gender_restriction' => $t->gender_restriction,
        'is_active' => (bool) $t->is_active,
    ])->toJson();
@endphp
<script>
(function() {
    const typesData = {!! $typesJson !!};
    const editForm = document.getElementById('form-lt-edit');
    const createForm = document.getElementById('form-lt-create');
    const modalTitle = document.getElementById('modal-lt-title');
    const drawer = document.getElementById('ltDrawer');

    function openDrawer() {
        document.getElementById('modal-lt').classList.remove('hidden');
        requestAnimationFrame(() => drawer.classList.remove('translate-x-full'));
    }

    window.closeLtDrawer = function() {
        drawer.classList.add('translate-x-full');
        setTimeout(() => document.getElementById('modal-lt').classList.add('hidden'), 300);
    };

    window.openCreateLt = function() {
        modalTitle.textContent = 'Add Leave Type';
        createForm.classList.remove('hidden');
        editForm.classList.add('hidden');
        createForm.reset();
        openDrawer();
    };

    window.openEditLt = function(id) {
        const t = typesData.find(x => x.id == id);
        if (!t) return;
        modalTitle.textContent = 'Edit Leave Type';
        createForm.classList.add('hidden');
        editForm.classList.remove('hidden');
        editForm.action = '{{ route("leave.types.update", "__ID__") }}'.replace('__ID__', id);
        editForm.querySelector('[name="name"]').value = t.name;
        editForm.querySelector('[name="code"]').value = t.code;
        editForm.querySelector('[name="days_per_year"]').value = t.days_per_year;
        editForm.querySelector('[name="gender_restriction"]').value = t.gender_restriction;
        editForm.querySelector('[name="is_paid"]').checked = !!t.is_paid;
        editForm.querySelector('[name="carry_forward"]').checked = !!t.carry_forward;
        editForm.querySelector('[name="requires_attachment"]').checked = !!t.requires_attachment;
        editForm.querySelector('[name="is_active"]').checked = !!t.is_active;
        openDrawer();
    };
})();
</script>
@endpush

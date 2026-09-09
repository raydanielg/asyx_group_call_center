@extends('layouts.dashboard')

@section('title', 'Leave Types - AYS Call Center')
@section('page_title', 'Leave · Types')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Types</h2>
        <p class="text-xs text-gray-500">Define leave categories and entitlements</p>
    </div>
    <button onclick="openModal('modal-lt')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Leave Type
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Code</th>
                    <th class="px-5 py-3 font-medium text-center">Days/Year</th>
                    <th class="px-5 py-3 font-medium text-center">Paid</th>
                    <th class="px-5 py-3 font-medium text-center">Carry Fwd</th>
                    <th class="px-5 py-3 font-medium text-center">Attachment</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $t)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors" id="lt-row-{{ $t->id }}">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $t->name }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ $t->code }}</span></td>
                    <td class="px-5 py-3 text-center text-xs text-gray-700 font-medium">{{ $t->days_per_year }}</td>
                    <td class="px-5 py-3 text-center">@if($t->is_paid)<span class="inline-flex items-center text-green-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>@else<span class="text-gray-300">—</span>@endif</td>
                    <td class="px-5 py-3 text-center">@if($t->carry_forward)<span class="inline-flex items-center text-green-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>@else<span class="text-gray-300">—</span>@endif</td>
                    <td class="px-5 py-3 text-center">@if($t->requires_attachment)<span class="inline-flex items-center text-amber-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></span>@else<span class="text-gray-300">—</span>@endif</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $t->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" onclick="openEditLt({{ $t->id }})" class="p-1.5 rounded-lg text-navy-600 hover:bg-navy-50 hover:text-navy-700 transition-colors" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('leave.types.destroy', $t) }}" class="inline" data-ajax data-confirm="Delete this leave type?" data-row-id="lt-row-{{ $t->id }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm font-medium">No leave types yet</p>
                    <p class="text-xs mt-1">Click "Add Leave Type" to create one.</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $types->links() }}</div>
</div>

<div id="modal-lt" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('modal-lt')"></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl mx-4">
        <div class="flex items-center gap-3 p-5 border-b border-gray-100">
            <div class="w-9 h-9 rounded-lg bg-navy-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900" id="modal-lt-title">Add Leave Type</h3>
            <button type="button" onclick="closeModal('modal-lt')" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-lt-create" method="POST" action="{{ route('leave.types.store') }}" class="p-5 space-y-3" data-ajax data-close-modal="modal-lt" data-reset-on-success="true">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required placeholder="e.g. Annual Leave, Sick Leave" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required placeholder="e.g. AL, SL, ML" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Days/Year <span class="text-red-500">*</span></label><input type="number" step="0.5" name="days_per_year" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Gender Restriction</label>
                    <select name="gender_restriction" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100">
                        <option value="any">Any</option><option value="male">Male</option><option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="is_paid" value="1" checked class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Paid</label>
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="carry_forward" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Carry Forward</label>
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="requires_attachment" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Requires Attachment</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Add Leave Type</button>
                <button type="button" onclick="closeModal('modal-lt')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </form>
        <form id="form-lt-edit" method="POST" class="p-5 space-y-3 hidden" data-ajax data-close-modal="modal-lt">
            @csrf @method('PUT')
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Days/Year <span class="text-red-500">*</span></label><input type="number" step="0.5" name="days_per_year" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Gender Restriction</label>
                    <select name="gender_restriction" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100">
                        <option value="any">Any</option><option value="male">Male</option><option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="is_paid" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Paid</label>
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="carry_forward" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Carry Forward</label>
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="requires_attachment" value="1" class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Requires Attachment</label>
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Active</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Save Changes</button>
                <button type="button" onclick="closeModal('modal-lt')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
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
        openModal('modal-lt');
    };

    document.querySelector('button[onclick="openModal(\'modal-lt\')"]')?.addEventListener('click', () => {
        modalTitle.textContent = 'Add Leave Type';
        createForm.classList.remove('hidden');
        editForm.classList.add('hidden');
    });
})();
</script>
@endendpush

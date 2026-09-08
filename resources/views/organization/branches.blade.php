@extends('layouts.dashboard')

@section('title', 'Branches - AYS Call Center')
@section('page_title', 'Organization · Branches')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Branches</h2>
        <p class="text-xs text-gray-500">Manage office locations</p>
    </div>
    <button onclick="openModal('modal-branch')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Branch
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Code</th>
                    <th class="px-5 py-3 font-medium">City</th>
                    <th class="px-5 py-3 font-medium">Phone</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors" id="row-branch-{{ $branch->id }}">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $branch->name }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ $branch->code ?? 'N/A' }}</span></td>
                    <td class="px-5 py-3 text-gray-500">{{ $branch->city ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $branch->phone ?? 'N/A' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $branch->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $branch->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" onclick="openEditBranch({{ $branch->id }})" class="p-1.5 rounded-lg text-navy-600 hover:bg-navy-50 hover:text-navy-700 transition-colors" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('organization.branches.destroy', $branch) }}" class="inline" data-ajax data-confirm="Delete this branch?" data-row-id="row-branch-{{ $branch->id }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No branches yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $branches->links() }}</div>
</div>

{{-- Add Modal --}}
<div id="modal-branch" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-branch')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Branch</h3>
        <form method="POST" action="{{ route('organization.branches.store') }}" class="space-y-3" data-ajax data-close-modal="modal-branch" data-reset-on-success="true">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code</label><input type="text" name="code" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Address</label><input type="text" name="address" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">City</label><input type="text" name="city" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Country</label><input type="text" name="country" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="closeModal('modal-branch')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

{-- Edit Modal --}}
<div id="modal-branch-edit" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('modal-branch-edit')"></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl mx-4">
        <div class="flex items-center gap-3 p-5 border-b border-gray-100">
            <div class="w-9 h-9 rounded-lg bg-navy-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">Edit Branch</h3>
            <button type="button" onclick="closeModal('modal-branch-edit')" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-branch-edit" method="POST" class="p-5 space-y-3" data-ajax data-close-modal="modal-branch-edit">
            @csrf @method('PUT')
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Address</label><input type="text" name="address" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">City</label><input type="text" name="city" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Timezone</label><input type="text" name="timezone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100"></div>
            <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer"><input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-navy-600 focus:ring-navy-300"> Active</label>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Save Changes</button>
                <button type="button" onclick="closeModal('modal-branch-edit')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@php($branchData = $branches->map(fn($b) => ['id' => $b->id, 'name' => $b->name, 'code' => $b->code, 'address' => $b->address, 'city' => $b->city, 'phone' => $b->phone, 'timezone' => $b->timezone, 'is_active' => $b->is_active])->values())
<script>
(function() {
    const data = @json($branchData);
    const editForm = document.getElementById('form-branch-edit');

    window.openEditBranch = function(id) {
        const b = data.find(x => x.id == id);
        if (!b) return;
        editForm.action = '{{ route("organization.branches.update", "__ID__") }}'.replace('__ID__', id);
        editForm.querySelector('[name="name"]').value = b.name || '';
        editForm.querySelector('[name="code"]').value = b.code || '';
        editForm.querySelector('[name="address"]').value = b.address || '';
        editForm.querySelector('[name="city"]').value = b.city || '';
        editForm.querySelector('[name="timezone"]').value = b.timezone || '';
        editForm.querySelector('[name="phone"]').value = b.phone || '';
        editForm.querySelector('[name="is_active"]').checked = !!b.is_active;
        openModal('modal-branch-edit');
    };
})();
</script>
@endendpush

@endsection

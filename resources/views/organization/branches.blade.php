@extends('layouts.dashboard')

@section('title', 'Branches - AYS Call Center')
@section('page_title', 'Organization · Branches')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Branches</h2>
            <p class="text-xs text-gray-500">Manage office locations</p>
        </div>
        <button onclick="openModal('modal-branch')"
            class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
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
                        <th class="px-5 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $branch->name }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $branch->code ?? 'N/A' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $branch->city ?? 'N/A' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $branch->phone ?? 'N/A' }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $branch->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $branch->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <button type="button" class="mr-3 text-xs text-navy-600 hover:text-navy-700 font-medium"
                                    onclick='openBranchEditModal(@js([
    'id' => $branch->id,
    'name' => $branch->name,
    'code' => $branch->code,
    'address' => $branch->address,
    'city' => $branch->city,
    'country' => $branch->country,
    'phone' => $branch->phone,
    'timezone' => $branch->timezone,
    'is_active' => (bool) $branch->is_active,
]))'>
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('organization.branches.destroy', $branch) }}"
                                    class="inline" data-ajax data-confirm="Delete this branch?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <p class="text-sm">No branches yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $branches->links() }}</div>
    </div>

    {{-- Add Modal --}}
    <div id="modal-branch" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-branch')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Branch</h3>
            <form method="POST" action="{{ route('organization.branches.store') }}" class="space-y-3" data-ajax
                data-close-modal="modal-branch" data-reset-on-success="true">
                @csrf
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input type="text" name="name" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Code</label><input type="text"
                        name="code"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Address</label><input type="text"
                        name="address"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">City</label><input type="text"
                            name="city"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Country</label><input type="text"
                            name="country"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Phone</label><input type="text"
                        name="phone"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Timezone</label><input type="text"
                        name="timezone" value="Africa/Dar_es_Salaam"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                    <button type="button" onclick="closeModal('modal-branch')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="modal-branch-edit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-branch-edit')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Edit Branch</h3>
            <form id="branch-edit-form" method="POST" action="" class="space-y-3" data-ajax
                data-close-modal="modal-branch-edit">
                @csrf
                @method('PUT')
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input id="edit-branch-name" type="text"
                        name="name" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span
                            class="text-red-500">*</span></label><input id="edit-branch-code" type="text"
                        name="code" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Address</label><input
                        id="edit-branch-address" type="text" name="address"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">City</label><input
                            id="edit-branch-city" type="text" name="city"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Country</label><input
                            id="edit-branch-country" type="text" name="country"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Phone</label><input
                        id="edit-branch-phone" type="text" name="phone"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Timezone</label><input
                        id="edit-branch-timezone" type="text" name="timezone"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <input type="hidden" name="is_active" value="0">
                <div class="flex items-center gap-2">
                    <input id="edit-branch-active" type="checkbox" name="is_active" value="1"
                        class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-500">
                    <label for="edit-branch-active" class="text-sm text-gray-700">Active</label>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Save
                        Changes</button>
                    <button type="button" onclick="closeModal('modal-branch-edit')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBranchEditModal(branch) {
            const form = document.getElementById('branch-edit-form');
            if (!form) return;

            form.action = `{{ url('/organization/branches') }}/${branch.id}`;
            document.getElementById('edit-branch-name').value = branch.name ?? '';
            document.getElementById('edit-branch-code').value = branch.code ?? '';
            document.getElementById('edit-branch-address').value = branch.address ?? '';
            document.getElementById('edit-branch-city').value = branch.city ?? '';
            document.getElementById('edit-branch-country').value = branch.country ?? '';
            document.getElementById('edit-branch-phone').value = branch.phone ?? '';
            document.getElementById('edit-branch-timezone').value = branch.timezone ?? 'Africa/Dar_es_Salaam';
            document.getElementById('edit-branch-active').checked = !!branch.is_active;

            openModal('modal-branch-edit');
        }
    </script>

@endsection

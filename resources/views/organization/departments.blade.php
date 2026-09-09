@extends('layouts.dashboard')

@section('title', 'Departments - AYS Call Center')
@section('page_title', 'Organization · Departments')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg fon
        t-bold text-gray-900">Departments</h2>
            <p class="text-xs text-gray-500">Manage organizational departments</p>
        </div>
        <button onclick="openModal('modal-dept')"
            class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Department
        </button>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-5 py-3 font-medium">Branch</th>
                        <th class="px-5 py-3 font-medium">Parent</th>
                        <th class="px-5 py-3 font-medium">Positions</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $dept->name }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $dept->branch?->name ?? 'N/A' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $dept->parent?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $dept->positions?->count() ?? 0 }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $dept->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                @php
                                    $deptPayload = [
                                        'id' => $dept->id,
                                        'name' => $dept->name,
                                        'code' => $dept->code,
                                        'branch_id' => $dept->branch_id,
                                        'parent_id' => $dept->parent_id,
                                        'is_active' => (bool) $dept->is_active,
                                    ];
                                @endphp
                                <button type="button" class="mr-3 text-xs text-navy-600 hover:text-navy-700 font-medium"
                                    data-dept='{{ json_encode($deptPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_TAG) }}'
                                    onclick="openDeptEditModal(this)">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('organization.departments.destroy', $dept) }}"
                                    class="inline" data-ajax data-confirm="Delete this department?">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <p class="text-sm">No departments yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $departments->links() }}</div>
    </div>

    <div id="modal-dept" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-dept')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Department</h3>
            <form method="POST" action="{{ route('organization.departments.store') }}" class="space-y-3" data-ajax
                data-close-modal="modal-dept" data-reset-on-success="true">
                @csrf
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input type="text" name="name" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Code</label><input type="text"
                        name="code"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Branch</label>
                    <select name="branch_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">Select...</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Parent Department</label>
                    <select name="parent_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">None</option>
                        @foreach ($allDepts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                    <button type="button" onclick="closeModal('modal-dept')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-dept-edit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-dept-edit')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Edit Department</h3>
            <form id="dept-edit-form" method="POST" action="" class="space-y-3" data-ajax
                data-close-modal="modal-dept-edit">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_active" value="0">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input id="edit-dept-name" type="text" name="name"
                        required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span
                            class="text-red-500">*</span></label><input id="edit-dept-code" type="text"
                        name="code" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Branch</label>
                    <select id="edit-dept-branch" name="branch_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">Select...</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Parent Department</label>
                    <select id="edit-dept-parent" name="parent_id"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">None</option>
                        @foreach ($allDepts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input id="edit-dept-active" type="checkbox" name="is_active" value="1"
                        class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-500">
                    <label for="edit-dept-active" class="text-sm text-gray-700">Active</label>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Save
                        Changes</button>
                    <button type="button" onclick="closeModal('modal-dept-edit')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openDeptEditModal = function(button) {
            const form = document.getElementById('dept-edit-form');
            if (!form) return;

            const dept = JSON.parse(button.getAttribute('data-dept') || '{}');

            form.action = `{{ url('/organization/departments') }}/${dept.id}`;
            document.getElementById('edit-dept-name').value = dept.name ?? '';
            document.getElementById('edit-dept-code').value = dept.code ?? '';
            document.getElementById('edit-dept-branch').value = dept.branch_id ?? '';
            document.getElementById('edit-dept-parent').value = dept.parent_id ?? '';
            document.getElementById('edit-dept-active').checked = !!dept.is_active;

            openModal('modal-dept-edit');
        }
    </script>

@endsection

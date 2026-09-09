@extends('layouts.dashboard')

@section('title', 'Working Hours - AYS Call Center')
@section('page_title', 'Organization · Working Hours')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Working Hour Policies</h2>
            <p class="text-xs text-gray-500">Define work schedules and grace periods</p>
        </div>
        <button onclick="openModal('modal-wh')"
            class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Policy
        </button>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-5 py-3 font-medium">Hours/Day</th>
                        <th class="px-5 py-3 font-medium">Days/Week</th>
                        <th class="px-5 py-3 font-medium">Week Start</th>
                        <th class="px-5 py-3 font-medium">Grace (min)</th>
                        <th class="px-5 py-3 font-medium">Overtime After</th>
                        <th class="px-5 py-3 font-medium">Default</th>
                        <th class="px-5 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($policies as $p)
                        <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $p->name }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->hours_per_day ?? 0 }}h</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->days_per_week ?? 0 }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ ucfirst($p->week_start ?? 'N/A') }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->grace_minutes ?? 0 }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->overtime_after_minutes ?? 0 }} min</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->is_default ? 'Yes' : 'No' }}</td>
                            <td class="px-5 py-3 text-right">
                                @php
                                    $policyPayload = [
                                        'id' => $p->id,
                                        'name' => $p->name,
                                        'hours_per_day' => $p->hours_per_day,
                                        'days_per_week' => $p->days_per_week,
                                        'week_start' => $p->week_start,
                                        'grace_minutes' => $p->grace_minutes,
                                        'overtime_after_minutes' => $p->overtime_after_minutes,
                                        'is_default' => (bool) $p->is_default,
                                    ];
                                @endphp
                                <button type="button" class="mr-3 text-xs text-navy-600 hover:text-navy-700 font-medium"
                                    data-policy='{{ json_encode($policyPayload, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_TAG) }}'
                                    onclick="openPolicyEditModal(this)">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('organization.working-hours.destroy', $p) }}"
                                    class="inline" data-ajax data-confirm="Delete this policy?">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                                <p class="text-sm">No policies yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $policies->links() }}</div>
    </div>

    <div id="modal-wh" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-wh')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Working Hour Policy</h3>
            <form method="POST" action="{{ route('organization.working-hours.store') }}" class="space-y-3" data-ajax
                data-close-modal="modal-wh" data-reset-on-success="true">
                @csrf
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input type="text" name="name" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Hours Per Day</label><input
                            type="number" step="0.5" name="hours_per_day" value="8" min="0" max="24"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Days Per Week</label><input
                            type="number" name="days_per_week" value="5" min="1" max="7"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Week Start</label><select
                            name="week_start"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                            <option value="mon">Mon</option>
                            <option value="tue">Tue</option>
                            <option value="wed">Wed</option>
                            <option value="thu">Thu</option>
                            <option value="fri">Fri</option>
                            <option value="sat">Sat</option>
                            <option value="sun">Sun</option>
                        </select></div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input
                            type="number" name="grace_minutes" value="0" min="0" max="120"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Overtime After (min)</label><input
                            type="number" name="overtime_after_minutes" value="480" min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div class="flex items-center gap-2 pt-6"><input type="hidden" name="is_default"
                            value="0"><input type="checkbox" name="is_default" value="1"
                            class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-500"><label
                            class="text-sm text-gray-700">Default Policy</label></div>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                    <button type="button" onclick="closeModal('modal-wh')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-wh-edit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-wh-edit')"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Edit Working Hour Policy</h3>
            <form id="wh-edit-form" method="POST" action="" class="space-y-3" data-ajax
                data-close-modal="modal-wh-edit">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_default" value="0">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span
                            class="text-red-500">*</span></label><input id="edit-wh-name" type="text" name="name"
                        required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Hours Per Day</label><input
                            id="edit-wh-hours" type="number" step="0.5" name="hours_per_day" min="0"
                            max="24"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Days Per Week</label><input
                            id="edit-wh-days" type="number" name="days_per_week" min="1" max="7"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Week Start</label><select
                            id="edit-wh-week-start" name="week_start"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                            <option value="mon">Mon</option>
                            <option value="tue">Tue</option>
                            <option value="wed">Wed</option>
                            <option value="thu">Thu</option>
                            <option value="fri">Fri</option>
                            <option value="sat">Sat</option>
                            <option value="sun">Sun</option>
                        </select></div>
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input
                            id="edit-wh-grace" type="number" name="grace_minutes" min="0" max="120"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-600 mb-1">Overtime After (min)</label><input
                            id="edit-wh-overtime" type="number" name="overtime_after_minutes" min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <div class="flex items-center gap-2 pt-6"><input id="edit-wh-default" type="checkbox"
                            name="is_default" value="1"
                            class="w-4 h-4 rounded border-gray-300 text-navy-600 focus:ring-navy-500"><label
                            for="edit-wh-default" class="text-sm text-gray-700">Default Policy</label></div>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Save
                        Changes</button>
                    <button type="button" onclick="closeModal('modal-wh-edit')"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openPolicyEditModal = function(button) {
            const form = document.getElementById('wh-edit-form');
            if (!form) return;

            const policy = JSON.parse(button.getAttribute('data-policy') || '{}');

            form.action = `{{ url('/organization/working-hours') }}/${policy.id}`;
            document.getElementById('edit-wh-name').value = policy.name ?? '';
            document.getElementById('edit-wh-hours').value = policy.hours_per_day ?? 8;
            document.getElementById('edit-wh-days').value = policy.days_per_week ?? 5;
            document.getElementById('edit-wh-week-start').value = policy.week_start ?? 'mon';
            document.getElementById('edit-wh-grace').value = policy.grace_minutes ?? 0;
            document.getElementById('edit-wh-overtime').value = policy.overtime_after_minutes ?? 480;
            document.getElementById('edit-wh-default').checked = !!policy.is_default;

            openModal('modal-wh-edit');
        }
    </script>

@endsection

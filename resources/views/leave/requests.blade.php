@extends('layouts.dashboard')

@section('title', 'Leave Requests - AYS Call Center')
@section('page_title', 'Leave · Requests')

@section('content')

@php
    $pendingCount = $requests->where('status', 'pending')->count();
    $approvedCount = $requests->where('status', 'approved')->count();
    $rejectedCount = $requests->where('status', 'rejected')->count();
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Requests</h2>
        <p class="text-xs text-gray-500">Manage and approve leave requests</p>
    </div>
    <button onclick="openCreateLr()" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Request
    </button>
</div>

{{-- Soft KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Pending</p>
                <p class="text-xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Approved</p>
                <p class="text-xl font-bold text-gray-900">{{ $approvedCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Rejected</p>
                <p class="text-xl font-bold text-gray-900">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $requests->total() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-50 overflow-hidden mb-4">
    <form method="GET" action="{{ route('leave.requests') }}" class="flex items-center gap-2 p-3">
        <select name="status" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 transition-all">
            <option value="">All Statuses</option>
            @foreach(['pending','approved','rejected','cancelled'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
        </button>
    </form>
</div>

{{-- Requests Table --}}
<div class="bg-white rounded-2xl border border-gray-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[10px] text-gray-400 uppercase tracking-wide bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Dates</th>
                    <th class="px-5 py-3 font-medium text-center">Days</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $lr)
                <tr class="border-t border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">{{ strtoupper(substr($lr->employee?->first_name ?? 'A', 0, 1)) }}</div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-gray-900">{{ $lr->employee?->first_name ?? '' }} {{ $lr->employee?->last_name ?? '' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $lr->employee?->employee_code ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs font-medium text-gray-700">{{ $lr->leaveType?->name ?? 'N/A' }}</div>
                        @if($lr->leaveType?->is_paid)
                        <div class="text-[10px] text-green-500 font-medium">Paid</div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="text-xs text-gray-600 font-medium">{{ $lr->start_date?->format('M d, Y') }}</div>
                        <div class="text-[10px] text-gray-400">to {{ $lr->end_date?->format('M d, Y') }}</div>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-50 text-xs font-bold text-gray-700">{{ $lr->days }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        @php $lrColors = ['pending'=>'amber','approved'=>'green','rejected'=>'red','cancelled'=>'gray']; @endphp
                        @php $color = $lrColors[$lr->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst($lr->status) }}</span>
                        @if($lr->decided_by)
                        <div class="text-[9px] text-gray-400 mt-0.5">by {{ $lr->decidedBy?->name ?? 'Unknown' }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('leave.employee.history', $lr->employee_id) }}" title="View History" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </a>
                            @if($lr->status === 'pending')
                            <form method="POST" action="{{ route('leave.requests.approve', $lr) }}" class="inline" data-ajax data-confirm="Approve this leave request?" data-confirm-text="Yes, approve" data-confirm-class="bg-green-500 hover:bg-green-600 text-white">
                                @csrf
                                <button type="submit" title="Approve" class="p-1.5 rounded-lg text-green-500 hover:bg-green-50 hover:text-green-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </form>
                            <button type="button" onclick="rejectLeave({{ $lr->id }})" title="Reject" class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @elseif($lr->status === 'approved')
                            <form method="POST" action="{{ route('leave.requests.cancel', $lr) }}" class="inline" data-ajax data-confirm="Cancel this approved leave?" data-confirm-text="Yes, cancel" data-confirm-class="bg-gray-500 hover:bg-gray-600 text-white">
                                @csrf
                                <button type="submit" title="Cancel Leave" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">No leave requests</p>
                    <p class="text-xs mt-1">Click "New Request" to create one.</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="px-5 py-3 border-t border-gray-50">{{ $requests->links() }}</div>
    @endif
</div>

{{-- New Request Drawer --}}
<div id="modal-lr" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-lr-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeLrDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="lrDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-lr-title">New Leave Request</h3>
            </div>
            <button type="button" onclick="closeLrDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('leave.requests.store') }}" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-lr" data-reset-on-success="true">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Employee <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    <option value="">Select employee...</option>
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Leave Type <span class="text-red-500">*</span></label>
                <select name="leave_type_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    <option value="">Select type...</option>
                    @foreach($types as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ $t->days_per_year }} days/yr)</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Half Day</label>
                <select name="half_day" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    <option value="none">No (Full Day)</option>
                    <option value="am">AM Only</option>
                    <option value="pm">PM Only</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Reason</label>
                <textarea name="reason" rows="3" placeholder="e.g. Family emergency, medical appointment..." class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeLrDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Submit Request</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Drawer --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-reject-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeRejectDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="rejectDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-reject-title">Reject Leave Request</h3>
            </div>
            <button type="button" onclick="closeRejectDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-reject" method="POST" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-reject">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Reason for Rejection <span class="text-red-500">*</span></label>
                <textarea name="decision_note" rows="4" required placeholder="e.g. Insufficient notice, not enough leave balance..." class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeRejectDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors">Reject Request</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const lrDrawer = document.getElementById('lrDrawer');
    const rejectDrawer = document.getElementById('rejectDrawer');

    function openDrawer(drawer, modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        requestAnimationFrame(() => drawer.classList.remove('translate-x-full'));
    }

    function closeDrawerFn(drawer, modalId) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => document.getElementById(modalId).classList.add('hidden'), 300);
    }

    window.openCreateLr = function() {
        openDrawer(lrDrawer, 'modal-lr');
    };

    window.closeLrDrawer = function() {
        closeDrawerFn(lrDrawer, 'modal-lr');
    };

    window.closeRejectDrawer = function() {
        closeDrawerFn(rejectDrawer, 'modal-reject');
    };

    window.rejectLeave = function(id) {
        const form = document.getElementById('form-reject');
        form.action = '{{ route("leave.requests.reject", "__ID__") }}'.replace('__ID__', id);
        openDrawer(rejectDrawer, 'modal-reject');
    };
})();
</script>
@endpush

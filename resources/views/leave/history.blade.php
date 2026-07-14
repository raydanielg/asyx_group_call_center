@extends('layouts.dashboard')

@section('title', 'Leave History - AYS Call Center')
@section('page_title', 'Leave · History')

@section('content')

{{-- Employee Header --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-lg shrink-0">
            {{ strtoupper(substr($employee->first_name ?? 'A', 0, 1)) }}
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
            <p class="text-xs text-gray-500">{{ $employee->employee_code ?? 'No code' }} · {{ ucfirst($employee->employment_status) }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('leave.requests') }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Requests
        </a>
        <a href="{{ route('leave.balances') }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Balances</a>
    </div>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-navy-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Total Requests</p>
                <p class="text-sm font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Pending</p>
                <p class="text-sm font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Approved</p>
                <p class="text-sm font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide">Rejected</p>
                <p class="text-sm font-bold text-gray-900">{{ $stats['rejected'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Leave Balances --}}
@if($balances->isNotEmpty())
<div class="bg-white rounded-xl border p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Leave Balances — {{ $year }}
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($balances as $bal)
            @php
                $total = $bal->entitled + $bal->carried_over;
                $remaining = $total - $bal->used;
                $usedPct = $total > 0 ? min(100, round(($bal->used / $total) * 100)) : 0;
            @endphp
            <div class="border border-gray-100 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-700">{{ $bal->leaveType?->name ?? 'Unknown' }}</span>
                    <span class="text-[10px] font-mono text-gray-400">{{ $bal->leaveType?->code ?? '' }}</span>
                </div>
                <div class="flex items-baseline gap-1 mb-2">
                    <span class="text-lg font-bold {{ $remaining > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $remaining }}</span>
                    <span class="text-xs text-gray-400">/ {{ $total }} days</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $remaining > 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-full transition-all" style="width: {{ $usedPct }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-1.5 text-[9px] text-gray-400">
                    <span>Used: {{ $bal->used }}</span>
                    @if($bal->carried_over > 0)<span>Carried: {{ $bal->carried_over }}</span>@endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Filter --}}
<div class="bg-white rounded-xl border overflow-hidden mb-4">
    <form method="GET" action="{{ route('leave.employee.history', $employee->id) }}" class="flex items-center gap-2 p-3">
        <select name="status" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected','cancelled'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
        </button>
    </form>
</div>

{{-- History Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Dates</th>
                    <th class="px-5 py-3 font-medium text-center">Days</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Reason</th>
                    <th class="px-5 py-3 font-medium">Decided By</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $lr)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="text-xs text-gray-700 font-medium">{{ $lr->leaveType?->name ?? 'N/A' }}</div>
                        @if($lr->leaveType?->is_paid)
                        <div class="text-[9px] text-green-500">Paid</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        <div>{{ $lr->start_date?->format('M d, Y') }}</div>
                        <div class="text-[10px] text-gray-400">to {{ $lr->end_date?->format('M d, Y') }}</div>
                        @if($lr->half_day && $lr->half_day !== 'none')
                        <div class="text-[9px] text-amber-500 font-medium uppercase">{{ $lr->half_day }} only</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-50 text-xs font-bold text-gray-700">{{ $lr->days }}</span>
                    </td>
                    <td class="px-5 py-3">
                        @php $lrColors = ['pending'=>'amber','approved'=>'green','rejected'=>'red','cancelled'=>'gray']; @endphp
                        @php $color = $lrColors[$lr->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($lr->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500 max-w-[200px]">
                        <div class="truncate">{{ $lr->reason ?? '—' }}</div>
                        @if($lr->decision_note)
                        <div class="text-[9px] text-red-400 truncate mt-0.5">Rejection: {{ $lr->decision_note }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        @if($lr->decided_by)
                        <div>{{ $lr->decidedBy?->name ?? 'Unknown' }}</div>
                        <div class="text-[9px] text-gray-400">{{ $lr->decided_at?->format('M d, Y') }}</div>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-1">
                            @if($lr->status === 'pending')
                            <form method="POST" action="{{ route('leave.requests.approve', $lr) }}" class="inline" data-ajax data-confirm="Approve this leave request?" data-confirm-text="Yes, approve" data-confirm-class="bg-green-500 hover:bg-green-600 text-white">
                                @csrf
                                <button type="submit" title="Approve" class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 hover:text-green-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </form>
                            <button type="button" onclick="rejectLeave({{ $lr->id }})" title="Reject" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @elseif($lr->status === 'approved')
                            <form method="POST" action="{{ route('leave.requests.cancel', $lr) }}" class="inline" data-ajax data-confirm="Cancel this approved leave?" data-confirm-text="Yes, cancel" data-confirm-class="bg-gray-500 hover:bg-gray-600 text-white">
                                @csrf
                                <button type="submit" title="Cancel Leave" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm font-medium">No leave requests for this employee</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $requests->links() }}</div>
</div>

{{-- Reject Modal --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('modal-reject')"></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl mx-4">
        <div class="flex items-center gap-3 p-5 border-b border-gray-100">
            <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">Reject Leave Request</h3>
            <button type="button" onclick="closeModal('modal-reject')" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-reject" method="POST" class="p-5 space-y-3" data-ajax data-close-modal="modal-reject">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                <textarea name="decision_note" rows="3" required placeholder="e.g. Insufficient notice, not enough leave balance..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 resize-none"></textarea>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Reject Request</button>
                <button type="button" onclick="closeModal('modal-reject')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
window.rejectLeave = function(id) {
    const form = document.getElementById('form-reject');
    form.action = '{{ route("leave.requests.reject", "__ID__") }}'.replace('__ID__', id);
    openModal('modal-reject');
};
</script>
@endendpush

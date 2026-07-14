@extends('layouts.dashboard')

@section('title', 'Leave Requests - AYS Call Center')
@section('page_title', 'Leave · Requests')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Requests</h2>
        <p class="text-xs text-gray-500">Manage and approve leave requests</p>
    </div>
    <button onclick="document.getElementById('modal-lr').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Request
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden mb-4">
    <form method="GET" action="{{ route('leave.requests') }}" class="flex items-center gap-2 p-3">
        <select name="status" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected','cancelled'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Dates</th>
                    <th class="px-5 py-3 font-medium">Days</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $lr)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $lr->employee?->first_name ?? '' }} {{ $lr->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $lr->leaveType?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $lr->start_date?->format('M d') }} - {{ $lr->end_date?->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $lr->days }}</td>
                    <td class="px-5 py-3">
                        @php $lrColors = ['pending'=>'amber','approved'=>'green','rejected'=>'red','cancelled'=>'gray']; @endphp
                        @php $color = $lrColors[$lr->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($lr->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs">
                        @if($lr->status === 'pending')
                            <form method="POST" action="{{ route('leave.requests.approve', $lr) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700 font-medium">Approve</button>
                            </form>
                            <span class="text-gray-200">|</span>
                            <button onclick="rejectLeave({{ $lr->id }})" class="text-red-500 hover:text-red-600 font-medium">Reject</button>
                        @elseif($lr->status === 'approved')
                            <form method="POST" action="{{ route('leave.requests.cancel', $lr) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-600 font-medium">Cancel</button>
                            </form>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No leave requests</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $requests->links() }}</div>
</div>

{{-- New Request Modal --}}
<div id="modal-lr" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-lr').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">New Leave Request</h3>
        <form method="POST" action="{{ route('leave.requests.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Employee <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Leave Type <span class="text-red-500">*</span></label>
                <select name="leave_type_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($types as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Date <span class="text-red-500">*</span></label><input type="date" name="start_date" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">End Date <span class="text-red-500">*</span></label><input type="date" name="end_date" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Reason</label><textarea name="reason" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Submit</button>
                <button type="button" onclick="document.getElementById('modal-lr').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

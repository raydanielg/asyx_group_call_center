@extends('layouts.dashboard')

@section('title', 'Leave Balances - AYS Call Center')
@section('page_title', 'Leave · Balances')

@section('content')

@php
    $totalEntitled = 0;
    $totalUsed = 0;
    $totalRemaining = 0;
    $employeesWithBalances = 0;
    foreach ($employees as $emp) {
        $hasBal = false;
        foreach ($types as $t) {
            $bal = $emp->leaveBalances->where('leave_type_id', $t->id)->first();
            if ($bal) {
                $total = $bal->entitled + $bal->carried_over;
                $rem = $total - $bal->used;
                $totalEntitled += $total;
                $totalUsed += $bal->used;
                $totalRemaining += $rem;
                $hasBal = true;
            }
        }
        if ($hasBal) $employeesWithBalances++;
    }
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Balances</h2>
        <p class="text-xs text-gray-500">Year {{ $year }} entitlements and usage</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="GET" action="{{ route('leave.balances') }}" class="flex items-center gap-2">
            <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 text-xs border border-gray-200 rounded-xl outline-none focus:border-navy-300 transition-all w-24">
            <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Apply
            </button>
        </form>
        <button onclick="openCreateLb()" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-xl hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Balance
        </button>
    </div>
</div>

{{-- Soft KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Entitled</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalEntitled }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Used</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalUsed }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Remaining</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalRemaining }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wide font-medium">Employees</p>
                <p class="text-xl font-bold text-gray-900">{{ $employeesWithBalances }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Balances Table --}}
<div class="bg-white rounded-2xl border border-gray-50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[10px] text-gray-400 uppercase tracking-wide bg-gray-50/50">
                    <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/50">Employee</th>
                    @foreach($types as $t)
                    <th class="px-5 py-3 font-medium text-center min-w-[120px]">
                        <div>{{ $t->code }}</div>
                        <div class="text-[9px] text-gray-400 font-normal normal-case">{{ $t->name }}</div>
                    </th>
                    @endforeach
                    <th class="px-5 py-3 font-medium text-center">History</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr class="border-t border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3.5 sticky left-0 bg-white">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}</div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $emp->employee_code ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach($types as $t)
                        @php $bal = $emp->leaveBalances->where('leave_type_id', $t->id)->first(); @endphp
                    <td class="px-5 py-3.5 text-center">
                        @if($bal)
                            @php
                                $total = $bal->entitled + $bal->carried_over;
                                $remaining = $total - $bal->used;
                                $usedPct = $total > 0 ? min(100, round(($bal->used / $total) * 100)) : 0;
                                $barColor = $remaining > 0 ? 'bg-green-500' : 'bg-red-500';
                            @endphp
                            <div class="flex flex-col items-center gap-1 cursor-pointer group" onclick="openEditLb({{ $bal->id }}, {{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', {{ $t->id }}, '{{ addslashes($t->code) }}', '{{ $bal->entitled }}', '{{ $bal->carried_over }}', '{{ $bal->used }}')">
                                <div class="text-xs font-bold {{ $remaining > 0 ? 'text-green-600' : 'text-red-500' }} group-hover:underline">{{ $remaining }}</div>
                                <div class="text-[9px] text-gray-400">/{{ $total }}</div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $usedPct }}%"></div>
                                </div>
                                <div class="flex items-center gap-1 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-[9px] text-navy-500 font-medium">Edit</span>
                                    <svg class="w-2.5 h-2.5 text-navy-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                            </div>
                        @else
                            <button onclick="openCreateForEmp({{ $emp->id }}, '{{ addslashes($emp->first_name . ' ' . $emp->last_name) }}', {{ $t->id }}, '{{ addslashes($t->code) }}')" class="text-gray-300 hover:text-navy-500 transition-colors" title="Add balance">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-5 py-3.5 text-center">
                        <a href="{{ route('leave.employee.history', $emp->id) }}" class="inline-flex items-center justify-center p-1.5 rounded-lg text-gray-400 hover:bg-navy-50 hover:text-navy-600 transition-colors" title="View Leave History">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="{{ count($types) + 2 }}" class="px-5 py-12 text-center text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">No employees</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
    <div class="px-5 py-3 border-t border-gray-50">{{ $employees->links() }}</div>
    @endif
</div>

@endsection

{{-- Create Balance Drawer --}}
<div id="modal-lb-create" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-lb-create-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeLbCreateDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="lbCreateDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900" id="modal-lb-create-title">Add Leave Balance</h3>
            </div>
            <button type="button" onclick="closeLbCreateDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-lb-create" method="POST" action="{{ route('leave.balances.store') }}" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-lb-create" data-reset-on-success="true">
            @csrf
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Employee <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    <option value="">Select employee...</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Leave Type <span class="text-red-500">*</span></label>
                <select name="leave_type_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    <option value="">Select leave type...</option>
                    @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->code }} — {{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Year <span class="text-red-500">*</span></label>
                <input type="number" name="year" value="{{ $year }}" required min="2020" max="2099" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Entitled <span class="text-red-500">*</span></label>
                    <input type="number" step="0.5" name="entitled" required min="0" value="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Carried</label>
                    <input type="number" step="0.5" name="carried_over" min="0" value="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Used</label>
                    <input type="number" step="0.5" name="used" min="0" value="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeLbCreateDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Create Balance</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Balance Drawer --}}
<div id="modal-lb-edit" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-lb-edit-title">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeLbEditDrawer()"></div>
    <div class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out" id="lbEditDrawer">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="modal-lb-edit-title">Edit Leave Balance</h3>
                    <p class="text-[10px] text-gray-400" id="lb-edit-context">—</p>
                </div>
            </div>
            <button type="button" onclick="closeLbEditDrawer()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Close">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-lb-edit" method="POST" class="flex-1 overflow-y-auto px-5 py-4 space-y-4" data-ajax data-close-modal="modal-lb-edit">
            @csrf @method('PUT')
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Entitled <span class="text-red-500">*</span></label>
                    <input type="number" step="0.5" name="entitled" required min="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Carried</label>
                    <input type="number" step="0.5" name="carried_over" min="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Used</label>
                    <input type="number" step="0.5" name="used" min="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 text-center">
                <span class="text-[10px] text-gray-400 uppercase font-medium">Remaining: </span>
                <span id="lb-edit-remaining" class="text-sm font-bold text-navy-600">0</span>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeLbEditDrawer()" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="button" id="lb-edit-delete" class="px-4 py-2.5 text-sm font-medium bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const createDrawer = document.getElementById('lbCreateDrawer');
    const editDrawer = document.getElementById('lbEditDrawer');
    const editForm = document.getElementById('form-lb-edit');
    const editContext = document.getElementById('lb-edit-context');
    const editRemaining = document.getElementById('lb-edit-remaining');
    const deleteBtn = document.getElementById('lb-edit-delete');
    let currentBalanceId = null;

    function openDrawer(drawer, modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        requestAnimationFrame(() => drawer.classList.remove('translate-x-full'));
    }

    function closeDrawerFn(drawer, modalId) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => document.getElementById(modalId).classList.add('hidden'), 300);
    }

    window.openCreateLb = function() {
        openDrawer(createDrawer, 'modal-lb-create');
    };

    window.closeLbCreateDrawer = function() {
        closeDrawerFn(createDrawer, 'modal-lb-create');
    };

    window.closeLbEditDrawer = function() {
        closeDrawerFn(editDrawer, 'modal-lb-edit');
    };

    function updateRemaining() {
        const entitled = parseFloat(editForm.querySelector('[name="entitled"]').value) || 0;
        const carried = parseFloat(editForm.querySelector('[name="carried_over"]').value) || 0;
        const used = parseFloat(editForm.querySelector('[name="used"]').value) || 0;
        const remaining = entitled + carried - used;
        editRemaining.textContent = remaining;
        editRemaining.className = 'text-sm font-bold ' + (remaining > 0 ? 'text-green-600' : 'text-red-500');
    }

    editForm.querySelectorAll('input').forEach(input => input.addEventListener('input', updateRemaining));

    window.openEditLb = function(balanceId, empId, empName, typeId, typeCode, entitled, carriedOver, used) {
        currentBalanceId = balanceId;
        editContext.textContent = empName + ' · ' + typeCode;
        editForm.action = '{{ route("leave.balances.update", "__ID__") }}'.replace('__ID__', balanceId);
        editForm.querySelector('[name="entitled"]').value = entitled;
        editForm.querySelector('[name="carried_over"]').value = carriedOver;
        editForm.querySelector('[name="used"]').value = used;
        updateRemaining();
        openDrawer(editDrawer, 'modal-lb-edit');
    };

    deleteBtn.addEventListener('click', function() {
        if (!currentBalanceId) return;
        const deleteUrl = '{{ route("leave.balances.destroy", "__ID__") }}'.replace('__ID__', currentBalanceId);
        confirmAction({
            title: 'Delete this leave balance?',
            text: 'This action cannot be undone. The balance record will be permanently removed.',
            confirmText: 'Yes, delete it!',
            icon: 'warning',
        }).then(result => {
            if (result.isConfirmed) {
                ajaxRequest(deleteUrl, 'DELETE').then(() => {
                    closeLbEditDrawer();
                });
            }
        });
    });

    window.openCreateForEmp = function(empId, empName, typeId, typeCode) {
        const createForm = document.getElementById('form-lb-create');
        createForm.querySelector('[name="employee_id"]').value = empId;
        createForm.querySelector('[name="leave_type_id"]').value = typeId;
        openDrawer(createDrawer, 'modal-lb-create');
    };
})();
</script>
@endpush

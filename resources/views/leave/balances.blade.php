@extends('layouts.dashboard')

@section('title', 'Leave Balances - AYS Call Center')
@section('page_title', 'Leave · Balances')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Balances</h2>
        <p class="text-xs text-gray-500">Year {{ $year }} entitlements and usage</p>
    </div>
    <form method="GET" action="{{ route('leave.balances') }}" class="flex items-center gap-2">
        <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 w-24">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Apply
        </button>
    </form>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/50">Employee</th>
                    @foreach($types as $t)
                    <th class="px-5 py-3 font-medium text-center min-w-[100px]">
                        <div>{{ $t->code }}</div>
                        <div class="text-[9px] text-gray-400 font-normal">{{ $t->name }}</div>
                    </th>
                    @endforeach
                    <th class="px-5 py-3 font-medium text-center">History</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 sticky left-0 bg-white">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[9px] shrink-0">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}</div>
                            <div class="min-w-0">
                                <div class="text-xs font-medium text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                <div class="text-[9px] text-gray-400">{{ $emp->employee_code ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    @foreach($types as $t)
                        @php $bal = $emp->leaveBalances->where('leave_type_id', $t->id)->first(); @endphp
                    <td class="px-5 py-3">
                        @if($bal)
                            @php
                                $total = $bal->entitled + $bal->carried_over;
                                $remaining = $total - $bal->used;
                                $usedPct = $total > 0 ? min(100, round(($bal->used / $total) * 100)) : 0;
                                $barColor = $remaining > 0 ? 'bg-green-500' : 'bg-red-500';
                            @endphp
                            <div class="flex flex-col items-center gap-1">
                                <div class="text-xs font-bold {{ $remaining > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $remaining }}</div>
                                <div class="text-[9px] text-gray-400">/{{ $total }}</div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $usedPct }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-gray-300">—</div>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('leave.employee.history', $emp->id) }}" class="inline-flex items-center justify-center p-1.5 rounded-lg text-gray-500 hover:bg-navy-50 hover:text-navy-600 transition-colors" title="View Leave History">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="{{ count($types) + 2 }}" class="px-5 py-10 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="text-sm font-medium">No employees</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $employees->links() }}</div>
</div>

@endsection

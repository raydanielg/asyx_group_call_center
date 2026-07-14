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
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    @foreach($types as $t)
                    <th class="px-5 py-3 font-medium text-center">{{ $t->code }}</th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    @foreach($types as $t)
                        @php $bal = $emp->leaveBalances->where('leave_type_id', $t->id)->first(); @endphp
                    <td class="px-5 py-3 text-center text-xs">
                        @if($bal)
                            <span class="font-medium {{ ($bal->entitled + $bal->carried_over - $bal->used) > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $bal->entitled + $bal->carried_over - $bal->used }}</span>
                            <span class="text-gray-400">/{{ $bal->entitled + $bal->carried_over }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr><td colspan="{{ count($types) + 1 }}" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No employees</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $employees->links() }}</div>
</div>

@endsection

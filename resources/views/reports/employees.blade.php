@extends('layouts.dashboard')

@section('title', 'Employee Report - AYS Call Center')
@section('page_title', 'Reports · Employees')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Employee Report</h2>
        <p class="text-xs text-gray-500">Headcount and movement analysis</p>
    </div>
    <form method="GET" action="{{ route('reports.employees') }}" class="flex items-center gap-2">
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total</div><div class="text-2xl font-bold text-gray-900 mt-1">{{ $total }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Active</div><div class="text-2xl font-bold text-green-600 mt-1">{{ $active }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Probation</div><div class="text-2xl font-bold text-amber-600 mt-1">{{ $onProbation }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Terminated</div><div class="text-2xl font-bold text-red-500 mt-1">{{ $terminated }}</div></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    {{-- By Department --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">By Department</h3>
        <div class="space-y-2">
            @php $maxDept = max($byDept ?: [1]); @endphp
            @foreach($byDept as $dept => $count)
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-gray-600 w-32 truncate shrink-0">{{ $dept }}</span>
                <div class="flex-1 h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-navy-400 to-navy-600 rounded-lg flex items-center px-2" style="width: {{ ($count / $maxDept) * 100 }}%"><span class="text-[10px] font-bold text-white">{{ $count }}</span></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- By Type --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">By Employment Type</h3>
        <div class="space-y-2">
            @php $maxType = max($byType ?: [1]); @endphp
            @foreach($byType as $type => $count)
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-gray-600 w-32 truncate shrink-0">{{ ucfirst(str_replace('_',' ',$type)) }}</span>
                <div class="flex-1 h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-copper-400 to-copper-600 rounded-lg flex items-center px-2" style="width: {{ ($count / $maxType) * 100 }}%"><span class="text-[10px] font-bold text-white">{{ $count }}</span></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Joiners & Leavers --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">New Joiners ({{ $joiners->count() }})</h3>
        <div class="space-y-1.5 max-h-60 overflow-y-auto">
            @forelse($joiners as $j)
            <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-2">
                <span class="font-medium text-gray-900">{{ $j->first_name }} {{ $j->last_name }}</span>
                <span class="text-gray-400">{{ $j->hire_date?->format('M d, Y') }}</span>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No joiners in this period</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Leavers ({{ $leavers->count() }})</h3>
        <div class="space-y-1.5 max-h-60 overflow-y-auto">
            @forelse($leavers as $l)
            <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-2">
                <span class="font-medium text-gray-900">{{ $l->first_name }} {{ $l->last_name }}</span>
                <span class="text-gray-400">{{ ucfirst(str_replace('_',' ',$l->employment_status)) }}</span>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">No leavers in this period</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

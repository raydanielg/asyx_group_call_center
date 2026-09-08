@extends('layouts.dashboard')

@section('title', 'Payroll Report - AYS Call Center')
@section('page_title', 'Reports · Payroll')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Payroll Report</h2>
        <p class="text-xs text-gray-500">Year {{ $year }}</p>
    </div>
    <form method="GET" action="{{ route('reports.payroll') }}" class="flex items-center gap-2">
        <input type="number" name="year" value="{{ $year }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300 w-24">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Apply</button>
    </form>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('reports.payroll.export', 'pdf') }}?year={{ $year }}" class="px-3 py-2 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>Export PDF</a>
    <a href="{{ route('reports.payroll.export', 'excel') }}?year={{ $year }}" class="px-3 py-2 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Export Excel</a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total Gross</div><div class="text-xl font-bold text-gray-900 mt-1">{{ number_format($totalGross, 0) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total Deductions</div><div class="text-xl font-bold text-red-500 mt-1">{{ number_format($totalDeductions, 0) }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total Net</div><div class="text-xl font-bold text-green-600 mt-1">{{ number_format($totalNet, 0) }}</div></div>
</div>

{{-- Monthly Chart --}}
<div class="bg-white rounded-xl border p-5 mb-4">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Monthly Payroll Trend</h3>
    @php $netMax = max(array_map(fn($m) => $m['net'], $monthlyData) ?: [1]); @endphp
    <div class="flex items-end gap-2 h-48">
        @foreach($monthlyData as $m)
        @php $pct = ($m['net'] / $netMax) * 100; @endphp
        <div class="flex-1 flex flex-col items-center gap-1" style="min-width: 8px;">
            <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                <div class="absolute bottom-0 left-0 right-0 rounded-t-md bg-gradient-to-t from-navy-600 to-navy-400" style="height: {{ max($pct, 1) }}%"></div>
            </div>
            <span class="text-[8px] text-gray-400 font-medium">{{ $m['month'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Monthly Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Month</th>
                    <th class="px-5 py-3 font-medium text-right">Employees</th>
                    <th class="px-5 py-3 font-medium text-right">Gross</th>
                    <th class="px-5 py-3 font-medium text-right">Deductions</th>
                    <th class="px-5 py-3 font-medium text-right">Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $m)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $m['month'] }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $m['employees'] }}</td>
                    <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ number_format($m['gross'], 0) }}</td>
                    <td class="px-5 py-3 text-right text-red-500 text-xs">{{ number_format($m['deductions'], 0) }}</td>
                    <td class="px-5 py-3 text-right font-medium text-gray-900 text-xs">{{ number_format($m['net'], 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

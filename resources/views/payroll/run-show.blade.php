@extends('layouts.dashboard')

@section('title', 'Payroll Run - AYS Call Center')
@section('page_title', 'Payroll · Run Details')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('payroll.runs') }}" class="text-xs text-gray-400 hover:text-navy-600">Payroll Runs</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">{{ $run->period_month }}/{{ $run->period_year }}</span>
    </div>
</div>

{{-- Run Header --}}
<div class="bg-white rounded-xl border p-5 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Payroll Run — {{ \Carbon\Carbon::create($run->period_year, $run->period_month)->format('F Y') }}</h2>
            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                <span>{{ $run->employee_count ?? 0 }} employees</span>
                <span>·</span>
                <span>Gross: {{ number_format($run->total_gross ?? 0, 2) }}</span>
                <span>·</span>
                <span>Deductions: {{ number_format($run->total_deductions ?? 0, 2) }}</span>
                <span>·</span>
                <span class="font-medium text-gray-900">Net: {{ number_format($run->total_net ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @php $stColors = ['draft'=>'gray','processing'=>'amber','review'=>'sky','approved'=>'navy','paid'=>'green']; @endphp
            @php $color = $stColors[$run->status] ?? 'gray'; @endphp
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($run->status) }}</span>
            @if($run->status === 'draft')
                <form method="POST" action="{{ route('payroll.runs.process', $run) }}" data-ajax data-confirm="Process this payroll run?">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Process</button>
                </form>
            @elseif($run->status === 'review')
                <form method="POST" action="{{ route('payroll.runs.approve', $run) }}" data-ajax data-confirm="Approve this payroll run?">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
                </form>
            @elseif($run->status === 'approved')
                <form method="POST" action="{{ route('payroll.runs.mark-paid', $run) }}" data-ajax data-confirm="Mark this payroll run as paid?">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700">Mark Paid</button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Payslips Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Payslips ({{ $run->payslips->count() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Department</th>
                    <th class="px-5 py-3 font-medium">Basic</th>
                    <th class="px-5 py-3 font-medium">Allowances</th>
                    <th class="px-5 py-3 font-medium">Deductions</th>
                    <th class="px-5 py-3 font-medium">OT</th>
                    <th class="px-5 py-3 font-medium">Bonus</th>
                    <th class="px-5 py-3 font-medium">Net Pay</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($run->payslips as $ps)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ps->employee?->first_name ?? '' }} {{ $ps->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ps->employee?->department?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ number_format($ps->basic_salary ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ number_format($ps->total_allowances ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-red-500 text-xs">{{ number_format($ps->total_deductions ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ number_format($ps->overtime_amount ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ number_format($ps->bonus ?? 0, 0) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ number_format($ps->net_pay ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('payroll.payslips.show', $ps) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No payslips yet. Click Process to generate.</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

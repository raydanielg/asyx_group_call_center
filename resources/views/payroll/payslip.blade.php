@extends('layouts.dashboard')

@section('title', 'Payslip - AYS Call Center')
@section('page_title', 'Payslip')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('payroll.runs.show', $payslip->payrollRun) }}" class="text-xs text-gray-400 hover:text-navy-600">Payroll Run</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">Payslip</span>
    </div>
</div>

<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="bg-white rounded-xl border p-5 mb-4">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $payslip->employee?->first_name ?? '' }} {{ $payslip->employee?->last_name ?? '' }}</h2>
                <p class="text-xs text-gray-500">{{ $payslip->employee?->position?->title ?? 'N/A' }} · {{ $payslip->employee?->department?->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $payslip->employee?->employee_code ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Period</p>
                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::create($payslip->payrollRun?->period_year, $payslip->payrollRun?->period_month)->format('F Y') }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-100 mt-1">{{ ucfirst($payslip->status) }}</span>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded-xl border p-4">
            <div class="text-[10px] font-medium text-gray-500 uppercase">Gross</div>
            <div class="text-lg font-bold text-gray-900 mt-1">{{ number_format($payslip->gross_pay ?? 0, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-[10px] font-medium text-gray-500 uppercase">Deductions</div>
            <div class="text-lg font-bold text-red-500 mt-1">{{ number_format($payslip->total_deductions ?? 0, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-[10px] font-medium text-gray-500 uppercase">Net Pay</div>
            <div class="text-lg font-bold text-green-600 mt-1">{{ number_format($payslip->net_pay ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- Breakdown --}}
    <div class="bg-white rounded-xl border p-5 mb-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Earnings & Deductions</h3>
        <div class="space-y-1.5">
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">Basic Salary</span>
                <span class="font-medium text-gray-900">{{ number_format($payslip->basic_salary ?? 0, 2) }}</span>
            </div>
            @foreach($payslip->lines->where('type', 'earning') as $line)
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">{{ $line->label }}</span>
                <span class="font-medium text-gray-900">{{ number_format($line->amount ?? 0, 2) }}</span>
            </div>
            @endforeach
            @if($payslip->overtime_amount > 0)
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">Overtime ({{ number_format($payslip->overtime_hours ?? 0, 1) }}h)</span>
                <span class="font-medium text-gray-900">{{ number_format($payslip->overtime_amount ?? 0, 2) }}</span>
            </div>
            @endif
            @if($payslip->bonus > 0)
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">Bonus</span>
                <span class="font-medium text-gray-900">{{ number_format($payslip->bonus ?? 0, 2) }}</span>
            </div>
            @endif
            @if($payslip->commission > 0)
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">Commission</span>
                <span class="font-medium text-gray-900">{{ number_format($payslip->commission ?? 0, 2) }}</span>
            </div>
            @endif
            @foreach($payslip->lines->where('type', 'deduction') as $line)
            <div class="flex justify-between text-xs py-1.5 border-b border-gray-50">
                <span class="text-gray-600">{{ $line->label }}</span>
                <span class="font-medium text-red-500">-{{ number_format($line->amount ?? 0, 2) }}</span>
            </div>
            @endforeach
            <div class="flex justify-between text-sm font-bold py-2 mt-2">
                <span class="text-gray-900">Net Pay</span>
                <span class="text-green-600">{{ number_format($payslip->net_pay ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Attendance Summary --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Attendance Summary</h3>
        <div class="grid grid-cols-4 gap-3 text-center">
            <div><div class="text-[10px] text-gray-500 uppercase">Working</div><div class="text-sm font-bold text-gray-900 mt-1">{{ $payslip->working_days ?? 0 }}</div></div>
            <div><div class="text-[10px] text-gray-500 uppercase">Present</div><div class="text-sm font-bold text-green-600 mt-1">{{ $payslip->present_days ?? 0 }}</div></div>
            <div><div class="text-[10px] text-gray-500 uppercase">Absent</div><div class="text-sm font-bold text-red-500 mt-1">{{ $payslip->absent_days ?? 0 }}</div></div>
            <div><div class="text-[10px] text-gray-500 uppercase">Leave</div><div class="text-sm font-bold text-purple-600 mt-1">{{ $payslip->leave_days ?? 0 }}</div></div>
        </div>
    </div>
</div>

@endsection

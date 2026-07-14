@extends('layouts.dashboard')

@section('title', 'Dashboard - AYS Call Center')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Hello {{ Auth::user()->first_name ?? Auth::user()->name ?? 'Admin' }}</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Here's your call center HRMS overview for {{ now()->format('M d, Y') }}.</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('reports.index') }}" class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="hidden sm:inline">Reports</span>
        </a>
        <a href="{{ route('employees.create') }}" class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">Add Employee</span>
        </a>
    </div>
</div>

{{-- KPI Stat Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    {{-- Total Employees --}}
    <div class="card-sm bg-gradient-to-br from-navy-600 to-navy-700 rounded-xl border border-navy-500 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-navy-100">Total Employees</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-navy-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($totalAgents) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-navy-200 font-medium relative z-10">Active workforce</div>
    </div>

    {{-- Attendance Today --}}
    <div class="card-sm bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl border border-sky-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-sky-100">Attendance Today</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $todayAttendancePct }}%</div>
        <div class="mt-1 text-[10px] sm:text-xs text-sky-100 font-medium relative z-10">{{ $todayPresent }}/{{ $todayTotal }} present · {{ $missingAttendance }} missing</div>
    </div>

    {{-- Pending Leaves --}}
    <div class="card-sm bg-gradient-to-br from-copper-400 to-copper-500 rounded-xl border border-copper-300 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-copper-50">Pending Leaves</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-copper-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($pendingLeaves) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-copper-50 font-medium relative z-10">Awaiting approval</div>
    </div>

    {{-- Calls Today --}}
    <div class="card-sm bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl border border-purple-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-purple-100">Calls Today</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($totalCallsToday) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-purple-100 font-medium relative z-10">AHT: {{ round($avgAht ?? 0) }}s · CSAT: {{ round($avgCsat ?? 0, 1) }}</div>
    </div>
</div>

{{-- Attendance Trend + Department Distribution --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Attendance Trend (30 days) --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Attendance Trend</h3>
                <p class="text-xs text-gray-400">Last 30 days</p>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-navy-500"></span>Present</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Late</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400"></span>Absent</span>
            </div>
        </div>
        @php
            $attMax = max(array_merge($presentData, $lateData, $absentData, [1]));
            $attCount = count($attendanceLabels);
        @endphp
        <div class="flex items-end gap-[1px] h-48">
            @for($i = 0; $i < $attCount; $i++)
                @php
                    $pPct = ($presentData[$i] / $attMax) * 100;
                    $lPct = ($lateData[$i] / $attMax) * 100;
                    $aPct = ($absentData[$i] / $attMax) * 100;
                    $totalPct = $pPct + $lPct + $aPct;
                @endphp
                <div class="flex-1 flex flex-col items-center group cursor-pointer relative" style="min-width: 4px;">
                    <div class="w-full bg-gray-50 rounded-t-sm relative h-44 overflow-hidden flex flex-col justify-end">
                        @if($totalPct > 0)
                            <div class="w-full bg-red-400" style="height: {{ max($aPct, 0) }}%"></div>
                            <div class="w-full bg-amber-400" style="height: {{ max($lPct, 0) }}%"></div>
                            <div class="w-full bg-navy-500" style="height: {{ max($pPct, 0) }}%"></div>
                        @endif
                    </div>
                    @if($i % 5 === 0)
                        <span class="text-[8px] text-gray-400 font-medium mt-1 whitespace-nowrap">{{ $attendanceLabels[$i] }}</span>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Department Distribution --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Headcount by Dept</h3>
            <p class="text-xs text-gray-400">Top departments</p>
        </div>
        @php $deptMax = max($deptCounts ?: [1]); @endphp
        <div class="space-y-3">
            @forelse($deptLabels as $i => $label)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-700 truncate">{{ $label }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $deptCounts[$i] }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-navy-500 to-navy-600 rounded-full transition-all duration-500" style="width: {{ ($deptCounts[$i] / $deptMax) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-8">No data yet</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Action Center --}}
<div class="bg-white rounded-xl border p-5 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-copper-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <h3 class="text-sm font-semibold text-gray-900">Action Center</h3>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <a href="{{ route('employees.documents-expiry') }}" class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-navy-200 hover:bg-navy-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Contracts Expiring</span>
                <span class="text-lg font-bold {{ $contractsExpiring > 0 ? 'text-red-500' : 'text-gray-300' }}">{{ $contractsExpiring }}</span>
            </div>
            <span class="text-[10px] text-gray-400">Within 30 days</span>
        </a>
        <a href="{{ route('employees.index') }}" class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-navy-200 hover:bg-navy-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Probations Ending</span>
                <span class="text-lg font-bold {{ $probationsEnding > 0 ? 'text-amber-500' : 'text-gray-300' }}">{{ $probationsEnding }}</span>
            </div>
            <span class="text-[10px] text-gray-400">Within 30 days</span>
        </a>
        <a href="{{ route('attendance.corrections') }}" class="flex flex-col gap-1 p-3 rounded-xl border border-gray-100 hover:border-navy-200 hover:bg-navy-50/30 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Unapproved OT</span>
                <span class="text-lg font-bold {{ $unapprovedOvertime > 0 ? 'text-copper-500' : 'text-gray-300' }}">{{ $unapprovedOvertime }}</span>
            </div>
            <span class="text-[10px] text-gray-400">Pending approval</span>
        </a>
    </div>
</div>

{{-- Payroll Trend + Recruitment Funnel --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-2 mb-6">
    {{-- Payroll Trend --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Payroll Trend</h3>
                <p class="text-xs text-gray-400">Last 12 months (net pay)</p>
            </div>
            @if($currentPayroll)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $currentPayroll->status === 'paid' ? 'bg-green-50 text-green-700' : ($currentPayroll->status === 'approved' ? 'bg-navy-50 text-navy-700' : 'bg-amber-50 text-amber-700') }} border border-current/20">{{ ucfirst($currentPayroll->status) }}</span>
            @endif
        </div>
        @php
            $payMax = max($payrollNet ?: [1]);
            $payCount = count($payrollLabels);
        @endphp
        <div class="flex items-end gap-1.5 h-40">
            @for($i = 0; $i < $payCount; $i++)
                @php $pct = ($payrollNet[$i] / $payMax) * 100; @endphp
                <div class="flex-1 flex flex-col items-center gap-1 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-copper-500 to-copper-400 group-hover:from-copper-600 group-hover:to-copper-500" style="height: {{ max($pct, 2) }}%"></div>
                    </div>
                    <span class="text-[8px] text-gray-400 font-medium whitespace-nowrap">{{ explode(' ', $payrollLabels[$i])[0] }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- Recruitment Funnel --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recruitment Funnel</h3>
                <p class="text-xs text-gray-400">{{ $openJobs }} open positions</p>
            </div>
            <a href="{{ route('recruitment.applicants') }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View all</a>
        </div>
        @php
            $funnelStages = [
                ['label' => 'Applied', 'count' => $applicantsApplied, 'color' => 'from-navy-400 to-navy-500'],
                ['label' => 'Screening', 'count' => $applicantsScreening, 'color' => 'from-sky-400 to-sky-500'],
                ['label' => 'Interview', 'count' => $applicantsInterview, 'color' => 'from-copper-400 to-copper-500'],
                ['label' => 'Offer', 'count' => $applicantsOffer, 'color' => 'from-purple-400 to-purple-500'],
                ['label' => 'Hired', 'count' => $applicantsHired, 'color' => 'from-green-400 to-green-500'],
            ];
            $funnelMax = max(array_column($funnelStages, 'count')) ?: 1;
        @endphp
        <div class="space-y-2.5">
            @foreach($funnelStages as $stage)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium text-gray-600 w-16 shrink-0">{{ $stage['label'] }}</span>
                    <div class="flex-1 h-7 bg-gray-50 rounded-lg overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r {{ $stage['color'] }} rounded-lg flex items-center px-2 transition-all duration-500" style="width: {{ max(($stage['count'] / $funnelMax) * 100, 5) }}%">
                            <span class="text-[10px] font-bold text-white">{{ $stage['count'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Performance Sparkline + Recent Hires --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Performance Trend --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Avg Performance Score</h3>
            <p class="text-xs text-gray-400">Last 6 months</p>
        </div>
        @php
            $perfMax = max($perfScores ?: [100]);
            $perfCount = count($perfLabels);
        @endphp
        <svg viewBox="0 0 100 40" class="w-full h-32" preserveAspectRatio="none">
            <defs>
                <linearGradient id="perfGrad" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#632871" stop-opacity="0.3"/>
                    <stop offset="100%" stop-color="#632871" stop-opacity="0"/>
                </linearGradient>
            </defs>
            @php
                $perfPts = [];
                for($i = 0; $i < $perfCount; $i++) {
                    $x = $perfCount > 1 ? round($i * (100 / ($perfCount - 1)), 2) : 50;
                    $y = round(38 - (($perfScores[$i] / 100) * 34), 2);
                    $perfPts[] = "{$x},{$y}";
                }
                $perfArea = "M" . $perfPts[0] . " L" . implode(" L", array_slice($perfPts, 1)) . " L100,40 L0,40 Z";
            @endphp
            <path d="{{ $perfArea }}" fill="url(#perfGrad)"/>
            <polyline points="{{ implode(' ', $perfPts) }}" fill="none" stroke="#632871" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
            @foreach($perfPts as $pt)
                @php list($px, $py) = explode(',', $pt); @endphp
                <circle cx="{{ $px }}" cy="{{ $py }}" r="1" fill="#632871"/>
            @endforeach
        </svg>
        <div class="flex justify-between mt-2">
            @foreach($perfLabels as $label)
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            @endforeach
        </div>
    </div>

    {{-- Recent Hires --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Hires</h3>
                <p class="text-xs text-gray-400">Latest employees who joined</p>
            </div>
            <a href="{{ route('employees.index') }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                        <th class="px-3 py-2 font-medium">Name</th>
                        <th class="px-3 py-2 font-medium">Department</th>
                        <th class="px-3 py-2 font-medium">Position</th>
                        <th class="px-3 py-2 font-medium">Hired</th>
                        <th class="px-3 py-2 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentHires as $emp)
                    <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[10px]">
                                    {{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}
                                </div>
                                <a href="{{ route('employees.show', $emp) }}" class="font-medium text-gray-900 hover:text-navy-600">{{ $emp->first_name ?? '' }} {{ $emp->last_name ?? '' }}</a>
                            </div>
                        </td>
                        <td class="px-3 py-2.5 text-gray-500">{{ $emp->department?->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2.5 text-gray-500">{{ $emp->position?->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2.5 text-gray-500">{{ $emp->hire_date?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="px-3 py-2.5">
                            @if($emp->employment_status === 'active')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-50 text-green-700 border border-green-100">Active</span>
                            @elseif($emp->employment_status === 'probation')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">Probation</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ ucfirst($emp->employment_status ?? 'N/A') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-8 text-center text-gray-400">
                            <p class="text-sm">No employees yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="h-16 lg:hidden"></div>

@endsection

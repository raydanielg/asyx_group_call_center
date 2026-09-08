@extends('layouts.dashboard')

@section('title', 'Reports - AYS Call Center')
@section('page_title', 'Reports · Center')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Report Center</h2>
    <p class="text-xs text-gray-500">Generate and view HRMS reports</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <a href="{{ route('reports.employees') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-navy-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Employee Report</h3>
        <p class="text-xs text-gray-500 mt-1">Headcount, department distribution, joiners & leavers</p>
    </a>

    <a href="{{ route('reports.attendance') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Attendance Report</h3>
        <p class="text-xs text-gray-500 mt-1">Present, late, absent rates, overtime summary</p>
    </a>

    <a href="{{ route('reports.payroll') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-copper-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-copper-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Payroll Report</h3>
        <p class="text-xs text-gray-500 mt-1">Monthly payroll trends, gross/net breakdown</p>
    </a>

    <a href="{{ route('reports.recruitment') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Recruitment Report</h3>
        <p class="text-xs text-gray-500 mt-1">Funnel, sources, conversion rates</p>
    </a>

    <a href="{{ route('reports.performance') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Performance Report</h3>
        <p class="text-xs text-gray-500 mt-1">Grade distribution, top performers</p>
    </a>

    <a href="{{ route('reports.call-center') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Call Center Report</h3>
        <p class="text-xs text-gray-500 mt-1">Call volumes, AHT, CSAT, conversions</p>
    </a>

    <a href="{{ route('reports.audit') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">Audit Log</h3>
        <p class="text-xs text-gray-500 mt-1">System activity and change tracking</p>
    </a>
</div>

<div class="mt-6 pt-6 border-t border-gray-200">
    <h3 class="text-sm font-semibold text-gray-900 mb-3">System Documents</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('reports.audit-report.download', 'pdf') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">System Audit Report (PDF)</h3>
            <p class="text-xs text-gray-500 mt-1">Full audit of permissions, accessibility, data quality</p>
        </a>
        <a href="{{ route('reports.audit-report.download', 'excel') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">System Audit Report (Excel)</h3>
            <p class="text-xs text-gray-500 mt-1">Spreadsheet version of the full system audit</p>
        </a>
        <a href="{{ route('reports.guide.download') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-navy-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">User Guide (PDF)</h3>
            <p class="text-xs text-gray-500 mt-1">Step-by-step manual for all roles</p>
        </a>
        <a href="{{ route('reports.system-overview') }}" class="bg-white rounded-xl border p-5 hover:border-navy-300 transition-colors group">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">System Overview</h3>
            <p class="text-xs text-gray-500 mt-1">Interactive presentation of all modules</p>
        </a>
    </div>
</div>

@endsection

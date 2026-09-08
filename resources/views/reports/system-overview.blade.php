@extends('layouts.dashboard')

@section('title', 'System Overview - AYS Call Center')
@section('page_title', 'System Overview')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">AYS Call Center HRMS — System Overview</h2>
    <p class="text-xs text-gray-500">Complete system inventory and architecture presentation</p>
</div>

{{-- Slide 1: Stats Grid --}}
<div class="bg-gradient-to-br from-navy-900 to-navy-700 rounded-2xl p-8 mb-6 text-white">
    <h3 class="text-xl font-bold mb-1">AYS Call Center HRMS</h3>
    <p class="text-navy-200 text-sm mb-6">Human Resource Management System for Call Center Operations</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['employees'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Employees</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['departments'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Departments</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['branches'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Branches</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['users'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Users</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['shifts'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Shifts</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['kpis'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">KPIs</div></div>
        <div class="bg-white/10 rounded-xl p-3 text-center"><div class="text-2xl font-bold">{{ $stats['audit_logs'] }}</div><div class="text-[10px] text-navy-200 uppercase mt-1">Audit Logs</div></div>
    </div>
</div>

{{-- Slide 2: Modules --}}
<div class="bg-white rounded-xl border p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">System Modules ({{ count($modules) }})</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($modules as $i => $module)
        <div class="border border-gray-100 rounded-xl p-4 hover:border-navy-200 transition-colors">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-6 h-6 rounded-lg bg-navy-50 flex items-center justify-center text-[10px] font-bold text-navy-600">{{ $i + 1 }}</span>
                <h4 class="text-sm font-semibold text-gray-900">{{ $module['name'] }}</h4>
            </div>
            <p class="text-xs text-gray-500">{{ $module['description'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Slide 3: Roles & Access --}}
<div class="bg-white rounded-xl border p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">User Roles & Access Levels</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach($roles as $role)
        <div class="border border-gray-100 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-{{ $role['color'] }}-50 text-{{ $role['color'] }}-700 border border-{{ $role['color'] }}-100">{{ $role['name'] }}</span>
            </div>
            <p class="text-xs text-gray-500">{{ $role['access'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Slide 4: Tech Stack --}}
<div class="bg-white rounded-xl border p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Technology Stack</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">Laravel 13</div><div class="text-[10px] text-gray-500 mt-1">PHP Framework</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">PHP 8.4</div><div class="text-[10px] text-gray-500 mt-1">Runtime</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">Tailwind CSS</div><div class="text-[10px] text-gray-500 mt-1">Styling</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">SweetAlert2</div><div class="text-[10px] text-gray-500 mt-1">Notifications</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">DomPDF</div><div class="text-[10px] text-gray-500 mt-1">PDF Export</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">PhpSpreadsheet</div><div class="text-[10px] text-gray-500 mt-1">Excel Export</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">SQLite</div><div class="text-[10px] text-gray-500 mt-1">Database</div></div>
        <div class="border border-gray-100 rounded-xl p-3 text-center"><div class="text-sm font-bold text-gray-900">Blade</div><div class="text-[10px] text-gray-500 mt-1">Templating</div></div>
    </div>
</div>

{{-- Slide 5: Security & Compliance --}}
<div class="bg-white rounded-xl border p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Security & Compliance</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Authentication</p><p class="text-[10px] text-gray-500">All routes protected with auth middleware</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Role-Based Access</p><p class="text-[10px] text-gray-500">Admin/owner middleware on sensitive routes</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Audit Logging</p><p class="text-[10px] text-gray-500">All CRUD operations logged with user, IP, timestamp</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Cookie Consent</p><p class="text-[10px] text-gray-500">GDPR-friendly consent banner, no tracking cookies</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Accessibility</p><p class="text-[10px] text-gray-500">Skip-link, ARIA labels, focus styles, color contrast</p></div>
        </div>
        <div class="flex items-start gap-3 p-3 border border-green-100 bg-green-50/50 rounded-xl">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><p class="text-xs font-semibold text-gray-900">Input Validation</p><p class="text-[10px] text-gray-500">All forms validated server-side</p></div>
        </div>
    </div>
</div>

{{-- Download Links --}}
<div class="flex flex-wrap items-center gap-3">
    <a href="{{ route('reports.audit-report.download', 'pdf') }}" class="px-4 py-2 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        Download Audit Report (PDF)
    </a>
    <a href="{{ route('reports.guide.download') }}" class="px-4 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        Download User Guide (PDF)
    </a>
</div>

@endsection

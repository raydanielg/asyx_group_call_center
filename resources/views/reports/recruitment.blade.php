@extends('layouts.dashboard')

@section('title', 'Recruitment Report - AYS Call Center')
@section('page_title', 'Reports · Recruitment')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Recruitment Report</h2>
    <p class="text-xs text-gray-500">Hiring funnel and source analysis</p>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Total Applicants</div><div class="text-xl font-bold text-navy-600 mt-1">{{ $totalApplicants }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">In Progress</div><div class="text-xl font-bold text-amber-600 mt-1">{{ $inProgress }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Hired</div><div class="text-xl font-bold text-green-600 mt-1">{{ $hired }}</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-[10px] font-medium text-gray-500 uppercase">Conversion Rate</div><div class="text-xl font-bold text-copper-600 mt-1">{{ $conversionRate }}%</div></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    {{-- By Source --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">By Source</h3>
        <div class="space-y-2">
            @php $maxSource = max($bySource ?: [1]); @endphp
            @foreach($bySource as $source => $count)
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-gray-600 w-24 truncate shrink-0">{{ ucfirst(str_replace('_',' ',$source)) }}</span>
                <div class="flex-1 h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg flex items-center px-2" style="width: {{ ($count / $maxSource) * 100 }}%"><span class="text-[10px] font-bold text-white">{{ $count }}</span></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- By Stage --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">By Stage (Funnel)</h3>
        <div class="space-y-2">
            @foreach(['applied','screening','interview','offer','hired','rejected'] as $stage)
            @php $count = $byStage[$stage] ?? 0; $maxStage = max($byStage ?: [1]); @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-gray-600 w-24 truncate shrink-0">{{ ucfirst($stage) }}</span>
                <div class="flex-1 h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-navy-400 to-navy-600 rounded-lg flex items-center px-2" style="width: {{ ($count / $maxStage) * 100 }}%"><span class="text-[10px] font-bold text-white">{{ $count }}</span></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Job Positions --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">Job Positions</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Title</th>
                    <th class="px-5 py-3 font-medium">Openings</th>
                    <th class="px-5 py-3 font-medium">Applicants</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $job->title }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $job->openings }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $job->applicants_count }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $job->status === 'open' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ ucfirst(str_replace('_',' ',$job->status)) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No job positions</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

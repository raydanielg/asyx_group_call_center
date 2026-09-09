@extends('layouts.dashboard')

@section('title', 'Data Entry - AYS Call Center')
@section('page_title', 'Analytics · Data Entry')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Call Center Data Entry</h2>
    <p class="text-xs text-gray-500">Manually enter or update daily agent statistics</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

    {{-- Entry Form --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-gray-50 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Enter Daily Stats</h3>
                    <p class="text-[10px] text-gray-400">Fill in the agent's daily call center metrics</p>
                </div>
            </div>

            <form id="dataEntryForm" method="POST" action="{{ route('analytics.data-entry') }}" class="p-5 space-y-5" data-ajax data-no-reload="true" data-reset-on-success="true">
                @csrf

                {{-- Agent & Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Agent <span class="text-red-500">*</span></label>
                        <select name="employee_id" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                            <option value="">Select agent...</option>
                            @foreach($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1.5 block">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ now()->toDateString() }}" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                    </div>
                </div>

                {{-- Call Metrics --}}
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase mb-2.5">Call Metrics</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Total Calls <span class="text-red-500">*</span></label>
                            <input type="number" name="total_calls" required min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Answered</label>
                            <input type="number" name="answered_calls" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Missed</label>
                            <input type="number" name="missed_calls" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Outbound</label>
                            <input type="number" name="outbound_calls" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Time Metrics --}}
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase mb-2.5">Time Metrics (seconds)</p>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Talk Time</label>
                            <input type="number" name="talk_time_seconds" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Hold Time</label>
                            <input type="number" name="hold_time_seconds" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Wrap Time</label>
                            <input type="number" name="wrap_time_seconds" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Quality Metrics --}}
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase mb-2.5">Quality Metrics</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">Conversions</label>
                            <input type="number" name="conversions" min="0" placeholder="0" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 mb-1 block">CSAT Score (0–5)</label>
                            <input type="number" step="0.01" min="0" max="5" name="csat_score" placeholder="0.00" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-navy-300 focus:ring-2 focus:ring-navy-100 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full px-4 py-3 text-sm font-bold text-white bg-navy-600 hover:bg-navy-700 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Stats
                </button>
            </form>
        </div>
    </div>

    {{-- Recent Entries --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-50 overflow-hidden sticky top-4">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Recent Entries</h3>
                    <p class="text-[10px] text-gray-400">Latest 20 saved records</p>
                </div>
            </div>
            <div id="recentEntries" class="p-3 space-y-2 max-h-[600px] overflow-y-auto">
                @forelse($recentStats as $stat)
                <div class="recent-entry flex items-center justify-between p-3 rounded-xl border border-gray-50 hover:border-gray-100 hover:bg-gray-50/30 transition-all" data-id="{{ $stat->id }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">{{ strtoupper(substr($stat->employee?->first_name ?? 'A', 0, 1)) }}</div>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-gray-900 truncate">{{ $stat->employee?->first_name ?? '' }} {{ $stat->employee?->last_name ?? '' }}</div>
                            <div class="text-[10px] text-gray-400">{{ $stat->date?->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-xs font-bold text-gray-900">{{ $stat->total_calls }} <span class="text-[10px] text-gray-400 font-normal">calls</span></div>
                        <div class="text-[10px] text-gray-400">AHT {{ $stat->aht_seconds }}s · CSAT {{ $stat->csat_score }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-14 h-14 rounded-full bg-gray-50 mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">No entries yet</p>
                    <p class="text-xs text-gray-400 mt-1">Saved stats will appear here</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('dataEntryForm').addEventListener('ajax:success', function(e) {
    const stat = e.detail.stat;
    if (!stat) return;

    const container = document.getElementById('recentEntries');
    const emptyState = container.querySelector('.text-center');
    if (emptyState) emptyState.remove();

    const existing = container.querySelector('[data-id="' + stat.id + '"]');
    const html = '<div class="recent-entry flex items-center justify-between p-3 rounded-xl border border-gray-50 hover:border-gray-100 hover:bg-gray-50/30 transition-all" data-id="' + stat.id + '">' +
        '<div class="flex items-center gap-2.5 min-w-0">' +
            '<div class="w-8 h-8 rounded-full bg-gradient-to-br from-navy-400 to-navy-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">' + (stat.employee ? stat.employee.first_name.charAt(0).toUpperCase() : 'A') + '</div>' +
            '<div class="min-w-0">' +
                '<div class="text-xs font-semibold text-gray-900 truncate">' + (stat.employee ? stat.employee.first_name + ' ' + stat.employee.last_name : '') + '</div>' +
                '<div class="text-[10px] text-gray-400">' + new Date(stat.date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) + '</div>' +
            '</div>' +
        '</div>' +
        '<div class="text-right shrink-0">' +
            '<div class="text-xs font-bold text-gray-900">' + stat.total_calls + ' <span class="text-[10px] text-gray-400 font-normal">calls</span></div>' +
            '<div class="text-[10px] text-gray-400">AHT ' + stat.aht_seconds + 's · CSAT ' + stat.csat_score + '</div>' +
        '</div>' +
    '</div>';

    if (existing) {
        existing.outerHTML = html;
    } else {
        container.insertAdjacentHTML('afterbegin', html);
    }
});
</script>
@endpush

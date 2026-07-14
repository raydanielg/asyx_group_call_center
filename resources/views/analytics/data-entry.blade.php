@extends('layouts.dashboard')

@section('title', 'Data Entry - AYS Call Center')
@section('page_title', 'Analytics · Data Entry')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Call Center Data Entry</h2>
    <p class="text-xs text-gray-500">Manually enter or update daily agent statistics</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Entry Form --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Enter Daily Stats</h3>
        <form method="POST" action="{{ route('analytics.data-entry') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Agent <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Date <span class="text-red-500">*</span></label><input type="date" name="date" value="{{ now()->toDateString() }}" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Total Calls <span class="text-red-500">*</span></label><input type="number" name="total_calls" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Answered</label><input type="number" name="answered_calls" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Missed</label><input type="number" name="missed_calls" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Outbound</label><input type="number" name="outbound_calls" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Talk (s)</label><input type="number" name="talk_time_seconds" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Hold (s)</label><input type="number" name="hold_time_seconds" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Wrap (s)</label><input type="number" name="wrap_time_seconds" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Conversions</label><input type="number" name="conversions" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">CSAT (0-5)</label><input type="number" step="0.01" min="0" max="5" name="csat_score" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Save Stats</button>
        </form>
    </div>

    {{-- Recent Entries --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Recent Entries</h3>
        <div class="space-y-2 max-h-[500px] overflow-y-auto">
            @forelse($recentStats as $stat)
            <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-2.5">
                <div>
                    <div class="font-medium text-gray-900">{{ $stat->employee?->first_name ?? '' }} {{ $stat->employee?->last_name ?? '' }}</div>
                    <div class="text-gray-400">{{ $stat->date?->format('M d, Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="font-medium text-gray-900">{{ $stat->total_calls }} calls</div>
                    <div class="text-gray-400">AHT {{ $stat->aht_seconds }}s · CSAT {{ $stat->csat_score }}</div>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-8">No entries yet</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

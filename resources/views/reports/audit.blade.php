@extends('layouts.dashboard')

@section('title', 'Audit Log - AYS Call Center')
@section('page_title', 'Reports · Audit')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Audit Log</h2>
        <p class="text-xs text-gray-500">System activity and change tracking</p>
    </div>
    <form method="GET" action="{{ route('reports.audit') }}" class="flex flex-wrap items-center gap-2">
        <input type="text" name="action" placeholder="Search action..." value="{{ request('action') }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Filter</button>
    </form>
</div>

<div class="flex items-center gap-2 mb-4">
    <a href="{{ route('reports.audit.export', 'pdf') }}?action={{ request('action') }}&from={{ request('from') }}&to={{ request('to') }}" class="px-3 py-2 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>Export PDF</a>
    <a href="{{ route('reports.audit.export', 'excel') }}?action={{ request('action') }}&from={{ request('from') }}&to={{ request('to') }}" class="px-3 py-2 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Export Excel</a>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Timestamp</th>
                    <th class="px-5 py-3 font-medium">User</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                    <th class="px-5 py-3 font-medium">Model</th>
                    <th class="px-5 py-3 font-medium">Record ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $log->occurred_at?->format('M d, Y H:i:s') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $log->user?->name ?? 'System' }}</td>
                    <td class="px-5 py-3 text-xs">
                        @php $actColors = ['create'=>'green','update'=>'sky','delete'=>'red','approve'=>'navy']; @endphp
                        @php $color = $actColors[$log->action] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($log->action) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $log->auditable_type ? class_basename($log->auditable_type) : 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $log->auditable_id ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No audit logs found</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
</div>

@endsection

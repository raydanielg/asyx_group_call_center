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

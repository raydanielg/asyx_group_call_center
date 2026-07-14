@extends('layouts.dashboard')

@section('title', 'KPI Targets - AYS Call Center')
@section('page_title', 'Performance · Targets')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">KPI Targets</h2>
        <p class="text-xs text-gray-500">Set performance targets by scope</p>
    </div>
    <button onclick="document.getElementById('modal-tgt').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Target
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">KPI</th>
                    <th class="px-5 py-3 font-medium">Scope</th>
                    <th class="px-5 py-3 font-medium">Target</th>
                    <th class="px-5 py-3 font-medium">Period</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($targets as $t)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $t->kpi?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ ucfirst($t->scope) }}
                        @if($t->department_id) · {{ $t->department?->name }} @endif
                        @if($t->team_id) · {{ $t->team?->name }} @endif
                        @if($t->employee_id) · {{ $t->employee?->first_name }} {{ $t->employee?->last_name }} @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $t->target_value }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $t->period_year }}{{ $t->period_month ? '/' . str_pad($t->period_month, 2, '0', STR_PAD_LEFT) : '' }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('performance.targets.destroy', $t) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No targets set</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $targets->links() }}</div>
</div>

<div id="modal-tgt" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-tgt').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5 max-h-[90vh] overflow-y-auto">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Target</h3>
        <form method="POST" action="{{ route('performance.targets.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">KPI <span class="text-red-500">*</span></label>
                <select name="kpi_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach($kpis as $k)<option value="{{ $k->id }}">{{ $k->name }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Scope <span class="text-red-500">*</span></label>
                <select name="scope" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300" onchange="toggleScopeFields(this.value)">
                    @foreach(['company','department','team','employee'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div id="dept-field" class="hidden"><label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                <select name="department_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                </select>
            </div>
            <div id="team-field" class="hidden"><label class="block text-xs font-medium text-gray-600 mb-1">Team</label>
                <select name="team_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                </select>
            </div>
            <div id="emp-field" class="hidden"><label class="block text-xs font-medium text-gray-600 mb-1">Employee</label>
                <select name="employee_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Year <span class="text-red-500">*</span></label><input type="number" name="period_year" value="{{ now()->year }}" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Month</label><input type="number" name="period_month" min="1" max="12" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Target Value <span class="text-red-500">*</span></label><input type="number" step="0.01" name="target_value" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-tgt').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleScopeFields(scope) {
    document.getElementById('dept-field').classList.toggle('hidden', scope !== 'department');
    document.getElementById('team-field').classList.toggle('hidden', scope !== 'team');
    document.getElementById('emp-field').classList.toggle('hidden', scope !== 'employee');
}
</script>

@endsection

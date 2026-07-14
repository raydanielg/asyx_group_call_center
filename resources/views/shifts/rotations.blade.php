@extends('layouts.dashboard')

@section('title', 'Shift Rotations - AYS Call Center')
@section('page_title', 'Shifts · Rotations')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Shift Rotations</h2>
        <p class="text-xs text-gray-500">Define recurring rotation patterns</p>
    </div>
    <button onclick="openModal('modal-rot')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Rotation
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Shift</th>
                    <th class="px-5 py-3 font-medium">Pattern</th>
                    <th class="px-5 py-3 font-medium">Department/Team</th>
                    <th class="px-5 py-3 font-medium">Start Date</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rotations as $r)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $r->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $r->shift?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ ucfirst(str_replace('_',' ',$r->pattern ?? 'weekly')) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $r->department?->name ?? $r->team?->name ?? 'All' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $r->start_date?->format('M d, Y') ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('shifts.rotations.destroy', $r) }}" class="inline" data-ajax data-confirm="Delete this rotation?">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No rotations yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $rotations->links() }}</div>
</div>

<div id="modal-rot" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-rot')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Rotation</h3>
        <form method="POST" action="{{ route('shifts.rotations.store') }}" class="space-y-3" data-ajax data-close-modal="modal-rot" data-reset-on-success="true">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Shift</label>
                <select name="shift_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach($shifts as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Pattern</label>
                <select name="pattern" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="weekly">Weekly</option>
                    <option value="biweekly">Biweekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">All</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Team</label>
                    <select name="team_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">All</option>
                        @foreach($teams as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label><input type="date" name="start_date" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="closeModal('modal-rot')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

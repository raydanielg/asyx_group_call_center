@extends('layouts.dashboard')

@section('title', 'Working Hours - AYS Call Center')
@section('page_title', 'Organization · Working Hours')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Working Hour Policies</h2>
        <p class="text-xs text-gray-500">Define work schedules and grace periods</p>
    </div>
    <button onclick="document.getElementById('modal-wh').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Policy
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Start</th>
                    <th class="px-5 py-3 font-medium">End</th>
                    <th class="px-5 py-3 font-medium">Grace (min)</th>
                    <th class="px-5 py-3 font-medium">Half Day</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($policies as $p)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $p->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $p->start_time ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $p->end_time ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $p->grace_minutes ?? 0 }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $p->half_day_hours ?? 'N/A' }}h</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('organization.working-hours.destroy', $p) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No policies yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $policies->links() }}</div>
</div>

<div id="modal-wh" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-wh').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Working Hour Policy</h3>
        <form method="POST" action="{{ route('organization.working-hours.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Time</label><input type="time" name="start_time" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">End Time</label><input type="time" name="end_time" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input type="number" name="grace_minutes" value="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Half Day Hours</label><input type="number" step="0.5" name="half_day_hours" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-wh').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

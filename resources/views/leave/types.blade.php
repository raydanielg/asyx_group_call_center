@extends('layouts.dashboard')

@section('title', 'Leave Types - AYS Call Center')
@section('page_title', 'Leave · Types')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Leave Types</h2>
        <p class="text-xs text-gray-500">Define leave categories and entitlements</p>
    </div>
    <button onclick="document.getElementById('modal-lt').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Leave Type
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Code</th>
                    <th class="px-5 py-3 font-medium">Days/Year</th>
                    <th class="px-5 py-3 font-medium">Paid</th>
                    <th class="px-5 py-3 font-medium">Carry Forward</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $t)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $t->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $t->code }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $t->days_per_year }}</td>
                    <td class="px-5 py-3">{{ $t->is_paid ? '<span class="text-green-600">Yes</span>' : '<span class="text-gray-400">No</span>' }}</td>
                    <td class="px-5 py-3">{{ $t->carry_forward ? '<span class="text-green-600">Yes</span>' : '<span class="text-gray-400">No</span>' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $t->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('leave.types.destroy', $t) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No leave types yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $types->links() }}</div>
</div>

<div id="modal-lt" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-lt').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Leave Type</h3>
        <form method="POST" action="{{ route('leave.types.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Days/Year <span class="text-red-500">*</span></label><input type="number" step="0.5" name="days_per_year" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Gender Restriction</label>
                <select name="gender_restriction" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="any">Any</option><option value="male">Male</option><option value="female">Female</option>
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="is_paid" value="1" checked> Paid</label>
                <label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="carry_forward" value="1"> Carry Forward</label>
                <label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="requires_attachment" value="1"> Requires Attachment</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-lt').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.dashboard')

@section('title', 'Shifts - AYS Call Center')
@section('page_title', 'Shifts · Catalog')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Shift Catalog</h2>
        <p class="text-xs text-gray-500">Define shift patterns</p>
    </div>
    <button onclick="openModal('modal-shift')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Shift
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($shifts as $shift)
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-gray-900">{{ $shift->name }}</h3>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $shift->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $shift->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="text-xs text-gray-500 space-y-1">
            <div><span class="text-gray-400">Start:</span> {{ $shift->start_time }}</div>
            <div><span class="text-gray-400">End:</span> {{ $shift->end_time }}</div>
            <div><span class="text-gray-400">Break:</span> {{ $shift->break_minutes ?? 0 }} min</div>
            <div><span class="text-gray-400">Grace:</span> {{ $shift->grace_minutes ?? 0 }} min</div>
        </div>
        <div class="mt-3">
            <form method="POST" action="{{ route('shifts.destroy', $shift) }}" class="inline" data-ajax data-confirm="Delete this shift?">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border p-10 text-center text-gray-400">
        <p class="text-sm">No shifts defined yet</p>
    </div>
    @endforelse
</div>

<div class="px-5 py-3 mt-4">{{ $shifts->links() }}</div>

<div id="modal-shift" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-shift')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Shift</h3>
        <form method="POST" action="{{ route('shifts.store') }}" class="space-y-3" data-ajax data-close-modal="modal-shift" data-reset-on-success="true">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Start Time</label><input type="time" name="start_time" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">End Time</label><input type="time" name="end_time" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Break (min)</label><input type="number" name="break_minutes" value="60" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Grace (min)</label><input type="number" name="grace_minutes" value="10" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Color</label><input type="text" name="color" placeholder="#0D3E63" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="closeModal('modal-shift')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

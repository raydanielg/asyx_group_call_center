@extends('layouts.dashboard')

@section('title', 'Coaching Notes - AYS Call Center')
@section('page_title', 'Quality · Coaching')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Coaching Notes</h2>
        <p class="text-xs text-gray-500">Track coaching and feedback sessions</p>
    </div>
    <a href="{{ route('quality.coaching.create') }}" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Note
    </a>
</div>

<div class="space-y-3">
    @forelse($notes as $note)
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-semibold text-gray-900">{{ $note->employee?->first_name ?? '' }} {{ $note->employee?->last_name ?? '' }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $note->status === 'open' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-green-50 text-green-700 border border-green-100' }}">{{ ucfirst($note->status) }}</span>
                </div>
                <p class="text-xs text-gray-600">{{ $note->note }}</p>
                @if($note->action_items)
                <div class="mt-2 text-xs text-gray-500">
                    <span class="font-medium text-gray-700">Action Items:</span> {{ $note->action_items }}
                </div>
                @endif
                @if($note->follow_up_date)
                <div class="mt-1 text-xs text-gray-400">Follow up: {{ $note->follow_up_date?->format('M d, Y') }}</div>
                @endif
                <div class="mt-2 text-[10px] text-gray-400">By {{ $note->createdBy?->name ?? 'N/A' }} · {{ $note->created_at?->format('M d, Y') }}</div>
            </div>
            @if($note->status === 'open')
            <form method="POST" action="{{ route('quality.coaching.done', $note) }}">
                @csrf
                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100">Mark Done</button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border p-10 text-center text-gray-400">
        <p class="text-sm">No coaching notes yet</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $notes->links() }}</div>

@endsection

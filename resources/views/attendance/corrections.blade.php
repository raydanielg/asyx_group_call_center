@extends('layouts.dashboard')

@section('title', 'Attendance Corrections - AYS Call Center')
@section('page_title', 'Attendance · Corrections')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Attendance Corrections</h2>
    <p class="text-xs text-gray-500">Review correction requests and history</p>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">Old → New</th>
                    <th class="px-5 py-3 font-medium">Reason</th>
                    <th class="px-5 py-3 font-medium">Corrected By</th>
                    <th class="px-5 py-3 font-medium">When</th>
                </tr>
            </thead>
            <tbody>
                @forelse($corrections as $c)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $c->record?->employee?->first_name ?? '' }} {{ $c->record?->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $c->record?->date?->format('M d, Y') ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-xs">
                        <span class="text-red-500">{{ $c->old_value ?? '—' }}</span>
                        <span class="text-gray-400">→</span>
                        <span class="text-green-600">{{ $c->new_value ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $c->reason ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $c->correctedBy?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $c->corrected_at?->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No corrections yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $corrections->links() }}</div>
</div>

@endsection

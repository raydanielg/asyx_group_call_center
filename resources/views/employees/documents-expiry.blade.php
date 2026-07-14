@extends('layouts.dashboard')

@section('title', 'Document Expiry - AYS Call Center')
@section('page_title', 'Document Expiry')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Expiring Documents</h2>
    <p class="text-xs text-gray-500">Documents expiring within the next 60 days</p>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Document</th>
                    <th class="px-5 py-3 font-medium">Category</th>
                    <th class="px-5 py-3 font-medium">Expires</th>
                    <th class="px-5 py-3 font-medium">Days Left</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiringDocs as $doc)
                @php $daysLeft = now()->diffInDays($doc->expires_at, false); @endphp
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('employees.show', $doc->employee) }}" class="font-medium text-gray-900 hover:text-navy-600">{{ $doc->employee?->first_name ?? '' }} {{ $doc->employee?->last_name ?? '' }}</a>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $doc->title }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ ucfirst($doc->category) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $doc->expires_at?->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $daysLeft <= 7 ? 'bg-red-50 text-red-700 border border-red-100' : ($daysLeft <= 30 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-green-50 text-green-700 border border-green-100') }}">{{ $daysLeft }} days</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        <p class="text-sm font-medium">No expiring documents</p>
                        <p class="text-xs mt-1">All documents are up to date.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

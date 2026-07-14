@extends('layouts.dashboard')

@section('title', 'Missing Attendance - AYS Call Center')
@section('page_title', 'Attendance · Missing')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Missing Attendance</h2>
    <p class="text-xs text-gray-500">Employees without attendance for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Department</th>
                    <th class="px-5 py-3 font-medium">Position</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missing as $emp)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $emp->department?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $emp->position?->title ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">All employees have attendance records ✅</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@extends('layouts.dashboard')

@section('title', 'Schedule Planner - AYS Call Center')
@section('page_title', 'Shifts · Planner')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Schedule Planner</h2>
        <p class="text-xs text-gray-500">Week of {{ $weekStart->format('M d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('shifts.planner', ['week' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">← Prev</a>
        <a href="{{ route('shifts.planner', ['week' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" class="px-3 py-2 text-xs border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Next →</a>
    </div>
</div>

<div class="bg-white rounded-xl border overflow-x-auto">
    <table class="w-full text-xs">
        <thead>
            <tr class="text-left text-gray-500 bg-gray-50/50 border-b border-gray-100">
                <th class="px-3 py-2 font-medium sticky left-0 bg-gray-50/50">Employee</th>
                @foreach($days as $day)
                <th class="px-3 py-2 font-medium text-center min-w-[100px]">
                    <div>{{ $day->format('D') }}</div>
                    <div class="text-[10px] text-gray-400">{{ $day->format('M d') }}</div>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
            <tr class="border-b border-gray-50 hover:bg-gray-50/30">
                <td class="px-3 py-2 font-medium text-gray-900 sticky left-0 bg-white">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[8px]">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}</div>
                        <span class="truncate max-w-[120px]">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                    </div>
                </td>
                @foreach($days as $day)
                @php $assignment = $assignments->get($emp->id . '-' . $day->toDateString()); @endphp
                <td class="px-2 py-2 text-center">
                    @if($assignment)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium text-white" style="background-color: {{ $assignment->shift?->color ?? '#0D3E63' }}">{{ $assignment->shift?->name ?? 'Shift' }}</span>
                    @else
                        <span class="text-gray-200">—</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @empty
            <tr><td colspan="8" class="px-3 py-10 text-center text-gray-400"><p class="text-sm">No active employees</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

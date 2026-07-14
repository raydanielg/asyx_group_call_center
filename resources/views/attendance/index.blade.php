@extends('layouts.dashboard')

@section('title', 'Attendance - AYS Call Center')
@section('page_title', 'Attendance · Daily Grid')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Daily Attendance Grid</h2>
        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</p>
    </div>
    <form method="GET" action="{{ route('attendance.index') }}" class="flex items-center gap-2">
        <select name="department_id" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Depts</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
    </form>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-4 py-3 font-medium">Employee</th>
                    <th class="px-4 py-3 font-medium">Dept</th>
                    <th class="px-4 py-3 font-medium">Shift</th>
                    <th class="px-4 py-3 font-medium">Check In</th>
                    <th class="px-4 py-3 font-medium">Check Out</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">OT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @php $rec = $records->get($emp->id); @endphp
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[9px]">{{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}</div>
                            <span class="font-medium text-gray-900 text-xs">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $emp->department?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $emp->shiftAssignments->first()?->shift?->name ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $rec?->check_in?->format('H:i') ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $rec?->check_out?->format('H:i') ?? '—' }}</td>
                    <td class="px-4 py-2.5">
                        @if($rec)
                            @php $stColors = ['present'=>'green','late'=>'amber','absent'=>'red','half_day'=>'sky','on_leave'=>'purple','off'=>'gray','holiday'=>'gray']; @endphp
                            @php $color = $stColors[$rec->status] ?? 'gray'; @endphp
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst(str_replace('_',' ',$rec->status)) }}</span>
                        @else
                            <span class="text-[10px] text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-xs">
                        @if($rec && $rec->overtime_minutes > 0)
                            <span class="text-copper-600 font-medium">{{ $rec->overtime_minutes }}m</span>
                            @if(!$rec->overtime_approved)
                                <form method="POST" action="{{ route('attendance.approve-overtime', $rec) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[9px] text-navy-600 hover:text-navy-700">Approve</button>
                                </form>
                            @else
                                <span class="text-[9px] text-green-600">✓</span>
                            @endif
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400"><p class="text-sm">No active employees</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

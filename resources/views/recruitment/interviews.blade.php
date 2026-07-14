@extends('layouts.dashboard')

@section('title', 'Interviews - AYS Call Center')
@section('page_title', 'Recruitment · Interviews')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Interviews</h2>
    <p class="text-xs text-gray-500">Scheduled and completed interviews</p>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Applicant</th>
                    <th class="px-5 py-3 font-medium">Round</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Scheduled</th>
                    <th class="px-5 py-3 font-medium">Interviewer</th>
                    <th class="px-5 py-3 font-medium">Score</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interviews as $int)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $int->applicant?->first_name ?? '' }} {{ $int->applicant?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $int->round }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ ucfirst($int->type) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $int->scheduled_at?->format('M d, Y H:i') }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $int->interviewer?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $int->score ? $int->score . '/100' : '—' }}</td>
                    <td class="px-5 py-3">
                        @php $stColors = ['scheduled'=>'amber','completed'=>'green','cancelled'=>'gray','no_show'=>'red']; @endphp
                        @php $color = $stColors[$int->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst(str_replace('_',' ',$int->status)) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No interviews yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $interviews->links() }}</div>
</div>

@endsection

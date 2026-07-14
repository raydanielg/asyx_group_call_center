@extends('layouts.dashboard')

@section('title', 'QA Evaluations - AYS Call Center')
@section('page_title', 'Quality · Evaluations')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">QA Evaluations</h2>
        <p class="text-xs text-gray-500">Call quality evaluations</p>
    </div>
    <a href="{{ route('quality.evaluations.create') }}" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Evaluation
    </a>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Agent</th>
                    <th class="px-5 py-3 font-medium">Form</th>
                    <th class="px-5 py-3 font-medium">Call Ref</th>
                    <th class="px-5 py-3 font-medium">Score</th>
                    <th class="px-5 py-3 font-medium">%</th>
                    <th class="px-5 py-3 font-medium">Outcome</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $ev)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ev->employee?->first_name ?? '' }} {{ $ev->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->form?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->call_reference ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->total_score }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ev->percentage }}%</td>
                    <td class="px-5 py-3">
                        @php $outColors = ['pass'=>'green','coaching_required'=>'amber','fail'=>'red']; @endphp
                        @php $color = $outColors[$ev->outcome] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst(str_replace('_',' ',$ev->outcome)) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->evaluated_at?->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('quality.evaluations.show', $ev) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No evaluations yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $evaluations->links() }}</div>
</div>

@endsection

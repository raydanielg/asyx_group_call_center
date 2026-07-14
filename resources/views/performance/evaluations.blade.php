@extends('layouts.dashboard')

@section('title', 'Evaluations - AYS Call Center')
@section('page_title', 'Performance · Evaluations')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Performance Evaluations</h2>
        <p class="text-xs text-gray-500">Generate and review evaluations</p>
    </div>
    <button onclick="openModal('modal-gen')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Generate
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Period</th>
                    <th class="px-5 py-3 font-medium">Score</th>
                    <th class="px-5 py-3 font-medium">Grade</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $ev)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900 text-xs">{{ $ev->employee?->first_name ?? '' }} {{ $ev->employee?->last_name ?? '' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $ev->period_year }}/{{ str_pad($ev->period_month, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $ev->weighted_score }}</td>
                    <td class="px-5 py-3">
                        @php $gradeColors = ['A'=>'green','B'=>'sky','C'=>'amber','D'=>'copper','E'=>'red']; @endphp
                        @php $gc = $gradeColors[$ev->grade] ?? 'gray'; @endphp
                        <span class="inline-flex items-center w-6 h-6 rounded-full text-[10px] font-bold bg-{{ $gc }}-50 text-{{ $gc }}-700 border border-{{ $gc }}-200 justify-center">{{ $ev->grade }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $ev->status === 'finalized' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">{{ ucfirst($ev->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('performance.evaluations.show', $ev) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No evaluations yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $evaluations->links() }}</div>
</div>

<div id="modal-gen" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-gen')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Generate Evaluations</h3>
        <form method="POST" action="{{ route('performance.evaluations.generate') }}" class="space-y-3" data-ajax data-close-modal="modal-gen" data-reset-on-success="true">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Year <span class="text-red-500">*</span></label><input type="number" name="period_year" value="{{ now()->year }}" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Month <span class="text-red-500">*</span></label>
                    <select name="period_month" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        @for($m = 1; $m <= 12; $m++)<option value="{{ $m }}" @selected($m === now()->month)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>@endfor
                    </select>
                </div>
            </div>
            <p class="text-[10px] text-gray-400">This will generate evaluations for all active employees based on their KPI targets and call center stats.</p>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Generate</button>
                <button type="button" onclick="closeModal('modal-gen')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

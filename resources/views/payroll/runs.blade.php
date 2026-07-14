@extends('layouts.dashboard')

@section('title', 'Payroll Runs - AYS Call Center')
@section('page_title', 'Payroll · Runs')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Payroll Runs</h2>
        <p class="text-xs text-gray-500">Create and manage payroll cycles</p>
    </div>
    <button onclick="openModal('modal-pr')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Run
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Period</th>
                    <th class="px-5 py-3 font-medium">Employees</th>
                    <th class="px-5 py-3 font-medium">Gross</th>
                    <th class="px-5 py-3 font-medium">Deductions</th>
                    <th class="px-5 py-3 font-medium">Net</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Processed By</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($runs as $run)
                @php $monthNames = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec']; @endphp
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $monthNames[$run->period_month] ?? '' }} {{ $run->period_year }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $run->employee_count ?? 0 }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ number_format($run->total_gross ?? 0, 0) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ number_format($run->total_deductions ?? 0, 0) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ number_format($run->total_net ?? 0, 0) }}</td>
                    <td class="px-5 py-3">
                        @php $stColors = ['draft'=>'gray','processing'=>'amber','review'=>'sky','approved'=>'navy','paid'=>'green']; @endphp
                        @php $color = $stColors[$run->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($run->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $run->processedBy?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('payroll.runs.show', $run) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No payroll runs yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $runs->links() }}</div>
</div>

<div id="modal-pr" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-pr')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">New Payroll Run</h3>
        <form method="POST" action="{{ route('payroll.runs.store') }}" class="space-y-3" data-ajax data-close-modal="modal-pr" data-reset-on-success="true">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Year <span class="text-red-500">*</span></label><input type="number" name="period_year" value="{{ now()->year }}" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Month <span class="text-red-500">*</span></label>
                    <select name="period_month" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        @for($m = 1; $m <= 12; $m++)<option value="{{ $m }}" @selected($m === now()->month)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>@endfor
                    </select>
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Create</button>
                <button type="button" onclick="closeModal('modal-pr')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

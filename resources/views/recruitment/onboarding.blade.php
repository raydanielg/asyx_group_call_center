@extends('layouts.dashboard')

@section('title', 'Onboarding - AYS Call Center')
@section('page_title', 'Recruitment · Onboarding')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-bold text-gray-900">Onboarding</h2>
    <p class="text-xs text-gray-500">Track new hire onboarding progress</p>
</div>

<div class="space-y-4">
    @forelse($onboardings as $ob)
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($ob->employee?->first_name ?? 'A', 0, 1)) }}</div>
                <div>
                    <a href="{{ route('employees.show', $ob->employee) }}" class="text-sm font-semibold text-gray-900 hover:text-navy-600">{{ $ob->employee?->first_name ?? '' }} {{ $ob->employee?->last_name ?? '' }}</a>
                    <p class="text-xs text-gray-500">{{ $ob->checklist?->name ?? 'Onboarding Checklist' }} · Started {{ $ob->started_at?->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php $total = $ob->tasks->count(); $done = $ob->tasks->where('is_done', true)->count(); $pct = $total > 0 ? round(($done / $total) * 100) : 0; @endphp
                <div class="text-right">
                    <div class="text-xs font-bold text-gray-900">{{ $done }}/{{ $total }}</div>
                    <div class="text-[10px] text-gray-400">{{ $pct }}% complete</div>
                </div>
                @php $stColors = ['in_progress'=>'amber','completed'=>'green','cancelled'=>'gray']; @endphp
                @php $color = $stColors[$ob->status] ?? 'gray'; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst(str_replace('_',' ',$ob->status)) }}</span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden mb-3">
            <div class="h-full bg-gradient-to-r from-navy-500 to-green-500 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
        </div>

        {{-- Tasks --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach($ob->tasks as $task)
            <div class="flex items-center gap-2 text-xs">
                <form method="POST" action="{{ route('recruitment.onboarding.toggle-task', $task) }}" class="inline" data-ajax>
                    @csrf
                    <button type="submit" class="w-4 h-4 rounded border {{ $task->is_done ? 'bg-green-500 border-green-500' : 'border-gray-300' }} flex items-center justify-center hover:border-navy-400 transition-colors">
                        @if($task->is_done)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                    </button>
                </form>
                <span class="{{ $task->is_done ? 'line-through text-gray-400' : 'text-gray-700' }}">{{ $task->task_title }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border p-10 text-center text-gray-400">
        <p class="text-sm">No onboarding records</p>
        <p class="text-xs mt-1">Onboarding tasks appear here when applicants are hired.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $onboardings->links() }}</div>

@endsection

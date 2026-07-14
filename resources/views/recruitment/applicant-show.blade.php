@extends('layouts.dashboard')

@section('title', 'Applicant Profile - AYS Call Center')
@section('page_title', 'Recruitment · Applicant')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('recruitment.applicants') }}" class="text-xs text-gray-400 hover:text-navy-600">Applicants</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">{{ $applicant->first_name }} {{ $applicant->last_name }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Left: Profile --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-lg">{{ strtoupper(substr($applicant->first_name ?? 'A', 0, 1)) }}</div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">{{ $applicant->first_name }} {{ $applicant->last_name }}</h2>
                    <p class="text-xs text-gray-500">{{ $applicant->jobPosition?->title ?? 'N/A' }}</p>
                </div>
            </div>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="text-gray-900 font-medium">{{ $applicant->email ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="text-gray-900 font-medium">{{ $applicant->phone ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Source</dt><dd class="text-gray-900 font-medium">{{ ucfirst(str_replace('_',' ',$applicant->source ?? '')) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Applied</dt><dd class="text-gray-900 font-medium">{{ $applicant->created_at?->format('M d, Y') }}</dd></div>
            </dl>
        </div>

        {{-- Stage Changer --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Change Stage</h3>
            <form method="POST" action="{{ route('recruitment.applicants.update-stage', $applicant) }}" class="space-y-3">
                @csrf
                <select name="stage" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300" onchange="this.form.submit()">
                    @foreach(['applied','screening','interview','offer','hired','rejected'] as $st)
                        <option value="{{ $st }}" @selected($applicant->stage === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                @if($applicant->stage !== 'rejected')
                <input type="text" name="rejected_reason" placeholder="Rejection reason (if rejecting)" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                @endif
            </form>
            @if($applicant->stage === 'hired')
                <p class="text-[10px] text-green-600 mt-2 font-medium">✓ Employee record created automatically</p>
            @endif
        </div>

        @if($applicant->notes)
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Notes</h3>
            <p class="text-xs text-gray-600">{{ $applicant->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Right: Interviews --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Interviews</h3>
            <div class="space-y-2">
                @forelse($applicant->interviews as $int)
                <div class="border border-gray-100 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-900">Round {{ $int->round }} · {{ ucfirst($int->type) }}</span>
                        @php $stColors = ['scheduled'=>'amber','completed'=>'green','cancelled'=>'gray','no_show'=>'red']; @endphp
                        @php $color = $stColors[$int->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst(str_replace('_',' ',$int->status)) }}</span>
                    </div>
                    <div class="text-xs text-gray-500">{{ $int->scheduled_at?->format('M d, Y H:i') }} · {{ $int->duration_minutes }}min</div>
                    @if($int->location)<div class="text-xs text-gray-500 mt-0.5">📍 {{ $int->location }}</div>@endif
                    @if($int->interviewer)<div class="text-xs text-gray-500 mt-0.5">👤 {{ $int->interviewer->name }}</div>@endif
                    @if($int->score)<div class="text-xs text-gray-500 mt-0.5">Score: <span class="font-bold text-gray-900">{{ $int->score }}/100</span></div>@endif
                    @if($int->feedback)<div class="text-xs text-gray-400 mt-1 italic">{{ $int->feedback }}</div>@endif
                </div>
                @empty
                <p class="text-xs text-gray-400">No interviews scheduled</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.dashboard')

@section('title', 'Applicants - AYS Call Center')
@section('page_title', 'Recruitment · Applicants')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Applicants</h2>
        <p class="text-xs text-gray-500">Track candidates through the hiring pipeline</p>
    </div>
    <button onclick="document.getElementById('modal-app').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Applicant
    </button>
</div>

{{-- Stage Summary --}}
<div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-4">
    @foreach(['applied','screening','interview','offer','hired','rejected'] as $stage)
    @php $stColors = ['applied'=>'navy','screening'=>'sky','interview'=>'copper','offer'=>'purple','hired'=>'green','rejected'=>'red']; @endphp
    @php $color = $stColors[$stage]; @endphp
    <div class="bg-white rounded-xl border p-3 text-center">
        <div class="text-lg font-bold text-{{ $color }}-600">{{ $stageCounts[$stage] ?? 0 }}</div>
        <div class="text-[10px] font-medium text-gray-500 uppercase mt-0.5">{{ ucfirst($stage) }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border p-3 mb-4">
    <form method="GET" action="{{ route('recruitment.applicants') }}" class="flex items-center gap-2">
        <select name="stage" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Stages</option>
            @foreach(['applied','screening','interview','offer','hired','rejected'] as $st)
                <option value="{{ $st }}" @selected(request('stage') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <select name="job_id" class="px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
            <option value="">All Jobs</option>
            @foreach($jobs as $j)<option value="{{ $j->id }}" @selected(request('job_id') == $j->id)>{{ $j->title }}</option>@endforeach
        </select>
        <button type="submit" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Job</th>
                    <th class="px-5 py-3 font-medium">Source</th>
                    <th class="px-5 py-3 font-medium">Stage</th>
                    <th class="px-5 py-3 font-medium">Applied</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($applicants as $app)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('recruitment.applicants.show', $app) }}" class="font-medium text-gray-900 hover:text-navy-600 text-xs">{{ $app->first_name }} {{ $app->last_name }}</a>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $app->jobPosition?->title ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ ucfirst(str_replace('_',' ',$app->source ?? '')) }}</td>
                    <td class="px-5 py-3">
                        @php $stColors = ['applied'=>'navy','screening'=>'sky','interview'=>'copper','offer'=>'purple','hired'=>'green','rejected'=>'red']; @endphp
                        @php $color = $stColors[$app->stage] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst($app->stage) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $app->created_at?->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('recruitment.applicants.show', $app) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No applicants yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $applicants->links() }}</div>
</div>

<div id="modal-app" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-app').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5 max-h-[90vh] overflow-y-auto">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Applicant</h3>
        <form method="POST" action="{{ route('recruitment.applicants.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Job Position <span class="text-red-500">*</span></label>
                <select name="job_position_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <option value="">Select...</option>
                    @foreach($jobs as $j)<option value="{{ $j->id }}">{{ $j->title }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">First Name <span class="text-red-500">*</span></label><input type="text" name="first_name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Last Name <span class="text-red-500">*</span></label><input type="text" name="last_name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Email</label><input type="email" name="email" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Source <span class="text-red-500">*</span></label>
                <select name="source" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    @foreach(['referral','walk_in','agency','online','other'] as $s)<option value="{{ $s }}">{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Notes</label><textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-app').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

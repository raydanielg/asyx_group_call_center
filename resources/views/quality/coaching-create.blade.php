@extends('layouts.dashboard')

@section('title', 'New Coaching Note - AYS Call Center')
@section('page_title', 'Quality · New Coaching')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('quality.coaching') }}" class="text-xs text-gray-400 hover:text-navy-600">Coaching</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">New</span>
    </div>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('quality.coaching.store') }}" class="space-y-4" data-ajax>
        @csrf
        @if($evaluationId)
        <input type="hidden" name="quality_evaluation_id" value="{{ $evaluationId }}">
        @endif

        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Coaching Note</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Agent <span class="text-red-500">*</span></label>
                    <select name="employee_id" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="">Select...</option>
                        @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Note <span class="text-red-500">*</span></label>
                    <textarea name="note" rows="4" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Action Items</label>
                    <textarea name="action_items" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Follow Up Date</label>
                    <input type="date" name="follow_up_date" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Save Note</button>
            <a href="{{ route('quality.coaching') }}" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
        </div>
    </form>
</div>

@endsection

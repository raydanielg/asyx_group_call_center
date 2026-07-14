@extends('layouts.dashboard')

@section('title', 'QA Form - AYS Call Center')
@section('page_title', 'Quality · Form')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('quality.forms') }}" class="text-xs text-gray-400 hover:text-navy-600">Forms</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">{{ $form->name }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Form Info --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ $form->name }}</h3>
        <p class="text-xs text-gray-500 mb-3">{{ $form->description ?? 'No description' }}</p>
        <dl class="space-y-2 text-xs">
            <div class="flex justify-between"><dt class="text-gray-500">Max Score</dt><dd class="text-gray-900 font-medium">{{ $form->max_score }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Criteria</dt><dd class="text-gray-900 font-medium">{{ $form->criteria->count() }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $form->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">{{ $form->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
        </dl>
    </div>

    {{-- Criteria --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Criteria</h3>
            <div class="space-y-2">
                @forelse($form->criteria as $c)
                <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-3">
                    <div>
                        <div class="font-medium text-gray-900">{{ $c->label }}</div>
                        <div class="text-gray-400">Weight: {{ $c->weight }} · Max: {{ $c->max_points }} pts</div>
                    </div>
                    <form method="POST" action="{{ route('quality.criteria.destroy', $c) }}" class="inline" data-ajax data-confirm="Delete this criterion?">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Delete</button>
                    </form>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-6">No criteria yet</p>
                @endforelse
            </div>

            <details class="mt-4">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Add Criterion</summary>
                <form method="POST" action="{{ route('quality.forms.criteria.store', $form) }}" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <input type="text" name="label" placeholder="Criterion label" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" step="0.01" name="weight" placeholder="Weight" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <input type="number" name="max_points" placeholder="Max Points" required class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                    </div>
                    <button type="submit" class="w-full px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add Criterion</button>
                </form>
            </details>
        </div>
    </div>
</div>

@endsection

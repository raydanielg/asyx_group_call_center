@extends('layouts.dashboard')

@section('title', 'QA Forms - AYS Call Center')
@section('page_title', 'Quality · Forms')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Evaluation Forms</h2>
        <p class="text-xs text-gray-500">Define QA scoring criteria</p>
    </div>
    <button onclick="document.getElementById('modal-form').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Form
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($forms as $form)
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center justify-between mb-2">
            <a href="{{ route('quality.forms.show', $form) }}" class="text-sm font-semibold text-gray-900 hover:text-navy-600">{{ $form->name }}</a>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $form->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $form->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
        <p class="text-xs text-gray-500 mb-2">{{ $form->description ?? 'No description' }}</p>
        <div class="flex items-center justify-between text-xs">
            <span class="text-gray-400">{{ $form->criteria->count() }} criteria</span>
            <span class="text-gray-400">Max: {{ $form->max_score }}</span>
        </div>
        <div class="mt-3">
            <form method="POST" action="{{ route('quality.forms.destroy', $form) }}" class="inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border p-10 text-center text-gray-400">
        <p class="text-sm">No forms yet</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $forms->links() }}</div>

<div id="modal-form" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-form').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Evaluation Form</h3>
        <form method="POST" action="{{ route('quality.forms.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Max Score <span class="text-red-500">*</span></label><input type="number" name="max_score" value="100" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Description</label><textarea name="description" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></textarea></div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-form').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

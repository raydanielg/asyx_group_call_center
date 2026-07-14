@extends('layouts.dashboard')

@section('title', 'Salary Components - AYS Call Center')
@section('page_title', 'Payroll · Components')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">Salary Components</h2>
        <p class="text-xs text-gray-500">Define allowances and deductions</p>
    </div>
    <button onclick="document.getElementById('modal-sc').classList.remove('hidden')" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Component
    </button>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Code</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Calc</th>
                    <th class="px-5 py-3 font-medium">Value</th>
                    <th class="px-5 py-3 font-medium">Taxable</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($components as $c)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $c->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $c->code }}</td>
                    <td class="px-5 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $c->type === 'allowance' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">{{ ucfirst($c->type) }}</span></td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ ucfirst(str_replace('_',' ',$c->calc_type ?? '')) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $c->calc_type === 'percent_of_basic' ? $c->value . '%' : number_format($c->value ?? 0, 2) }}</td>
                    <td class="px-5 py-3">{{ $c->is_taxable ? '<span class="text-green-600 text-xs">Yes</span>' : '<span class="text-gray-400 text-xs">No</span>' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $c->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">{{ $c->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('payroll.components.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400"><p class="text-sm">No components yet</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $components->links() }}</div>
</div>

<div id="modal-sc" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modal-sc').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Add Salary Component</h3>
        <form method="POST" action="{{ route('payroll.components.store') }}" class="space-y-3">
            @csrf
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Name <span class="text-red-500">*</span></label><input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Code <span class="text-red-500">*</span></label><input type="text" name="code" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                    <select name="type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="allowance">Allowance</option><option value="deduction">Deduction</option>
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Calc Type</label>
                    <select name="calc_type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300">
                        <option value="fixed">Fixed</option><option value="percent_of_basic">% of Basic</option><option value="formula">Formula</option>
                    </select>
                </div>
            </div>
            <div><label class="block text-xs font-medium text-gray-600 mb-1">Value <span class="text-red-500">*</span></label><input type="number" step="0.01" name="value" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg outline-none focus:border-navy-300"></div>
            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="is_taxable" value="1"> Taxable</label>
                <label class="flex items-center gap-2 text-xs text-gray-600"><input type="checkbox" name="is_statutory" value="1"> Statutory</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                <button type="button" onclick="document.getElementById('modal-sc').classList.add('hidden')" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

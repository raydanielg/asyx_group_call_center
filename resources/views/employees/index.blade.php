@extends('layouts.dashboard')

@section('title', 'Employees - AYS Call Center')
@section('page_title', 'Employees')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-gray-900">All Employees</h2>
        <p class="text-xs text-gray-500">Manage your workforce</p>
    </div>
    <a href="{{ route('employees.create') }}" class="px-3 py-2 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5 self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Employee
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border p-4 mb-4">
    <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, code, phone..." class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
        <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            <option value="">All Status</option>
            @foreach(['active','probation','suspended','on_leave','terminated','resigned'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
            @endforeach
        </select>
        <select name="department_id" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
        <select name="employment_type" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            <option value="">All Types</option>
            @foreach(['full_time','part_time','contract','intern'] as $et)
                <option value="{{ $et }}" @selected(request('employment_type') === $et)>{{ ucfirst(str_replace('_',' ',$et)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Filter</button>
    </form>
</div>

{{-- Employees Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Employee</th>
                    <th class="px-5 py-3 font-medium">Department</th>
                    <th class="px-5 py-3 font-medium">Position</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Hired</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[10px]">
                                {{ strtoupper(substr($emp->first_name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('employees.show', $emp) }}" class="font-medium text-gray-900 hover:text-navy-600 block">{{ $emp->first_name ?? '' }} {{ $emp->last_name ?? '' }}</a>
                                <span class="text-[10px] text-gray-400">{{ $emp->employee_code ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $emp->department?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $emp->position?->title ?? $emp->position?->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ ucfirst(str_replace('_',' ',$emp->employment_type ?? '')) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $emp->hire_date?->format('M d, Y') ?? 'N/A' }}</td>
                    <td class="px-5 py-3">
                        @php $stColors = ['active'=>'green','probation'=>'amber','suspended'=>'red','on_leave'=>'sky','terminated'=>'gray','resigned'=>'gray']; @endphp
                        @php $color = $stColors[$emp->employment_status ?? 'active'] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst(str_replace('_',' ',$emp->employment_status ?? 'N/A')) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('employees.show', $emp) }}" class="text-xs font-medium text-navy-600 hover:text-navy-700">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        <p class="text-sm font-medium">No employees found</p>
                        <p class="text-xs mt-1">Try adjusting filters or add a new employee.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $employees->withQueryString()->links() }}
    </div>
</div>

@endsection

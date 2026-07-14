@extends('layouts.dashboard')

@section('title', 'Add Employee - AYS Call Center')
@section('page_title', 'Add Employee')

@section('content')

<div class="mb-6">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('employees.index') }}" class="text-xs text-gray-400 hover:text-navy-600">Employees</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">Add New</span>
    </div>
    <h2 class="text-lg font-bold text-gray-900">Add New Employee</h2>
    <p class="text-xs text-gray-500">Fill in the employee details below</p>
</div>

<form method="POST" action="{{ route('employees.store') }}" class="space-y-4" data-ajax>
    @csrf

    {{-- Personal Info --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Gender</label>
                <select name="gender" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach(['male','female','other'] as $g)
                        <option value="{{ $g }}" @selected(old('gender') === $g)>{{ ucfirst($g) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Marital Status</label>
                <select name="marital_status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach(['single','married','divorced','widowed'] as $ms)
                        <option value="{{ $ms }}" @selected(old('marital_status') === $ms)>{{ ucfirst($ms) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">National ID</label>
                <input type="text" name="national_id" value="{{ old('national_id') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">NSSF Number</label>
                <input type="text" name="nssf_number" value="{{ old('nssf_number') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">TIN Number</label>
                <input type="text" name="tin_number" value="{{ old('tin_number') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
        </div>
    </div>

    {{-- Contact Info --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Contact Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Alt Phone</label>
                <input type="text" name="alt_phone" value="{{ old('alt_phone') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Country</label>
                <input type="text" name="country" value="{{ old('country') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
        </div>
    </div>

    {{-- Employment Info --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Employment Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Branch</label>
                <select name="branch_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" @selected(old('branch_id') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                <select name="department_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" @selected(old('department_id') == $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Position</label>
                <select name="position_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach($positions as $p)
                        <option value="{{ $p->id }}" @selected(old('position_id') == $p->id)>{{ $p->title ?? $p->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Team</label>
                <select name="team_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach($teams as $t)
                        <option value="{{ $t->id }}" @selected(old('team_id') == $t->id)>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Reports To</label>
                <select name="reports_to" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    <option value="">Select...</option>
                    @foreach($managers as $m)
                        <option value="{{ $m->id }}" @selected(old('reports_to') == $m->id)>{{ $m->first_name }} {{ $m->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Employment Type <span class="text-red-500">*</span></label>
                <select name="employment_type" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none" required>
                    <option value="">Select...</option>
                    @foreach(['full_time','part_time','contract','intern'] as $et)
                        <option value="{{ $et }}" @selected(old('employment_type') === $et)>{{ ucfirst(str_replace('_',' ',$et)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Hire Date <span class="text-red-500">*</span></label>
                <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Probation End Date</label>
                <input type="date" name="probation_end_date" value="{{ old('probation_end_date') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Contract End Date</label>
                <input type="date" name="contract_end_date" value="{{ old('contract_end_date') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
        </div>
    </div>

    {{-- Salary Info --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Salary Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Base Salary</label>
                <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Pay Frequency</label>
                <select name="pay_frequency" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">
                    @foreach(['monthly','biweekly','weekly'] as $pf)
                        <option value="{{ $pf }}" @selected(old('pay_frequency') === $pf)>{{ ucfirst($pf) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="bg-white rounded-xl border p-5">
        <label class="block text-xs font-medium text-gray-600 mb-1">Notes</label>
        <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-navy-300 focus:ring-2 focus:ring-navy-100 outline-none">{{ old('notes') }}</textarea>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors">Create Employee</button>
        <a href="{{ route('employees.index') }}" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
    </div>
</form>

@endsection

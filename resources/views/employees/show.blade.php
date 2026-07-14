@extends('layouts.dashboard')

@section('title', $employee->first_name . ' ' . $employee->last_name . ' - AYS Call Center')
@section('page_title', 'Employee Profile')

@section('content')

<div class="mb-4">
    <div class="flex items-center gap-2 mb-1">
        <a href="{{ route('employees.index') }}" class="text-xs text-gray-400 hover:text-navy-600">Employees</a>
        <span class="text-gray-300">/</span>
        <span class="text-xs font-medium text-gray-700">{{ $employee->first_name }} {{ $employee->last_name }}</span>
    </div>
</div>

{{-- Profile Header --}}
<div class="bg-white rounded-xl border p-5 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-xl shrink-0">
            {{ strtoupper(substr($employee->first_name ?? 'A', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->last_name }}</h2>
            <p class="text-sm text-gray-500">{{ $employee->position?->title ?? $employee->position?->name ?? 'N/A' }} · {{ $employee->department?->name ?? 'N/A' }}</p>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-navy-50 text-navy-700 border border-navy-100">{{ $employee->employee_code ?? 'N/A' }}</span>
                @php $stColors = ['active'=>'green','probation'=>'amber','suspended'=>'red','on_leave'=>'sky','terminated'=>'gray','resigned'=>'gray']; @endphp
                @php $color = $stColors[$employee->employment_status ?? 'active'] ?? 'gray'; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">{{ ucfirst(str_replace('_',' ',$employee->employment_status ?? 'N/A')) }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ ucfirst(str_replace('_',' ',$employee->employment_type ?? '')) }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('employees.edit', $employee) }}" class="px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            @if(!in_array($employee->employment_status, ['terminated','resigned']))
            <button type="button" onclick="openModal('terminateModal')" class="px-3 py-2 text-xs font-medium bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Terminate
            </button>
            @endif
        </div>
    </div>
</div>

{{-- Termination Modal --}}
@if(!in_array($employee->employment_status, ['terminated','resigned']))
<div id="terminateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('terminateModal')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md animate-fade">
        <div class="flex items-center gap-3 p-5 border-b border-gray-100">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Terminate Employee</h3>
                <p class="text-xs text-gray-500">This action will end employment for {{ $employee->first_name }} {{ $employee->last_name }}</p>
            </div>
            <button type="button" onclick="closeModal('terminateModal')" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('employees.terminate', $employee) }}" class="p-5 space-y-4" data-ajax data-confirm="Are you sure you want to terminate {{ $employee->first_name }} {{ $employee->last_name }}?" data-confirm-text="Yes, terminate" data-confirm-icon="warning" data-confirm-class="bg-red-500 hover:bg-red-600 text-white" data-close-modal="terminateModal">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Termination Date <span class="text-red-500">*</span></label>
                <input type="date" name="termination_date" value="{{ old('termination_date', date('Y-m-d')) }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-red-300 focus:ring-2 focus:ring-red-100 outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Termination Reason <span class="text-red-500">*</span></label>
                <textarea name="termination_reason" rows="4" placeholder="e.g. End of contract, misconduct, redundancy..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:border-red-300 focus:ring-2 focus:ring-red-100 outline-none resize-none" required>{{ old('termination_reason') }}</textarea>
            </div>
            <div class="flex items-center gap-2 pt-1">
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors inline-flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Terminate Employee
                </button>
                <button type="button" onclick="closeModal('terminateModal')" class="px-4 py-2.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Left: Personal & Contact --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Personal Info</h3>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Gender</dt><dd class="text-gray-900 font-medium">{{ ucfirst($employee->gender ?? 'N/A') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Date of Birth</dt><dd class="text-gray-900 font-medium">{{ $employee->date_of_birth?->format('M d, Y') ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Marital Status</dt><dd class="text-gray-900 font-medium">{{ ucfirst($employee->marital_status ?? 'N/A') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">National ID</dt><dd class="text-gray-900 font-medium">{{ $employee->national_id ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">NSSF Number</dt><dd class="text-gray-900 font-medium">{{ $employee->nssf_number ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">TIN Number</dt><dd class="text-gray-900 font-medium">{{ $employee->tin_number ?? 'N/A' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Contact</h3>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="text-gray-900 font-medium truncate">{{ $employee->email ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="text-gray-900 font-medium">{{ $employee->phone ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Alt Phone</dt><dd class="text-gray-900 font-medium">{{ $employee->alt_phone ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd class="text-gray-900 font-medium text-right">{{ $employee->address ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">City</dt><dd class="text-gray-900 font-medium">{{ $employee->city ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Country</dt><dd class="text-gray-900 font-medium">{{ $employee->country ?? 'N/A' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Employment</h3>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Branch</dt><dd class="text-gray-900 font-medium">{{ $employee->branch?->name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Team</dt><dd class="text-gray-900 font-medium">{{ $employee->team?->name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Reports To</dt><dd class="text-gray-900 font-medium">{{ $employee->reportsTo?->first_name ?? '' }} {{ $employee->reportsTo?->last_name ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Hire Date</dt><dd class="text-gray-900 font-medium">{{ $employee->hire_date?->format('M d, Y') ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Probation End</dt><dd class="text-gray-900 font-medium">{{ $employee->probation_end_date?->format('M d, Y') ?? 'N/A' }}</dd></div>
                @if(in_array($employee->employment_status, ['terminated','resigned']))
                <div class="flex justify-between"><dt class="text-gray-500">Termination Date</dt><dd class="text-gray-900 font-medium">{{ $employee->termination_date?->format('M d, Y') ?? 'N/A' }}</dd></div>
                @endif
            </dl>
            @if(in_array($employee->employment_status, ['terminated','resigned']) && $employee->termination_reason)
            <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-lg">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="text-[10px] font-semibold text-red-600 uppercase tracking-wide">Termination Reason</p>
                        <p class="text-xs text-gray-700 mt-0.5">{{ $employee->termination_reason }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Emergency Contacts --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900">Emergency Contacts</h3>
            </div>
            <div class="space-y-2">
                @forelse($employee->emergencyContacts as $ec)
                    <div class="text-xs border border-gray-100 rounded-lg p-2.5">
                        <div class="font-medium text-gray-900">{{ $ec->name }}</div>
                        <div class="text-gray-500">{{ $ec->relationship }} · {{ $ec->phone }}</div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No contacts added</p>
                @endforelse
            </div>
            <details class="mt-3">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Add Contact</summary>
                <form method="POST" action="{{ route('employees.emergency-contacts.store', $employee) }}" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <input type="text" name="name" placeholder="Name" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300" required>
                    <input type="text" name="relationship" placeholder="Relationship" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300" required>
                    <input type="text" name="phone" placeholder="Phone" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none focus:border-navy-300" required>
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                </form>
            </details>
        </div>
    </div>

    {{-- Middle: Documents, Contracts, Salaries, Bank --}}
    <div class="space-y-4">
        {{-- Documents --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Documents</h3>
            <div class="space-y-2">
                @forelse($employee->documents as $doc)
                    <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-2.5">
                        <div>
                            <div class="font-medium text-gray-900">{{ $doc->title }}</div>
                            <div class="text-gray-400">{{ ucfirst($doc->category) }} · {{ $doc->created_at?->format('M d, Y') }}</div>
                        </div>
                        @if($doc->file_path)
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-navy-600 hover:text-navy-700 font-medium">View</a>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No documents uploaded</p>
                @endforelse
            </div>
            <details class="mt-3">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Upload Document</summary>
                <form method="POST" action="{{ route('employees.documents.store', $employee) }}" enctype="multipart/form-data" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <select name="category" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                        @foreach(['contract','cv','certificate','id_copy','warning_letter','other'] as $cat)
                            <option value="{{ $cat }}">{{ ucfirst(str_replace('_',' ',$cat)) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="title" placeholder="Title" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <input type="file" name="file" class="w-full text-xs" required>
                    <input type="date" name="expires_at" placeholder="Expiry Date" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none">
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Upload</button>
                </form>
            </details>
        </div>

        {{-- Contracts --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Contracts</h3>
            <div class="space-y-2">
                @forelse($employee->contracts as $contract)
                    <div class="text-xs border border-gray-100 rounded-lg p-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900">{{ ucfirst($contract->contract_type) }}</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium {{ $contract->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">{{ ucfirst($contract->status) }}</span>
                        </div>
                        <div class="text-gray-400 mt-0.5">{{ $contract->start_date?->format('M d, Y') }} → {{ $contract->end_date?->format('M d, Y') ?? 'Open' }}</div>
                        <div class="text-gray-500 mt-0.5">Salary: {{ number_format($contract->base_salary ?? 0, 2) }}</div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No contracts added</p>
                @endforelse
            </div>
            <details class="mt-3">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Add Contract</summary>
                <form method="POST" action="{{ route('employees.contracts.store', $employee) }}" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <select name="contract_type" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                        @foreach(['permanent','fixed_term','probation'] as $ct)
                            <option value="{{ $ct }}">{{ ucfirst($ct) }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" placeholder="Start Date" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <input type="date" name="end_date" placeholder="End Date" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none">
                    <input type="number" step="0.01" name="base_salary" placeholder="Base Salary" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                </form>
            </details>
        </div>

        {{-- Salary History --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Salary History</h3>
            <div class="space-y-2">
                @forelse($employee->salaries as $sal)
                    <div class="flex items-center justify-between text-xs border border-gray-100 rounded-lg p-2.5">
                        <div>
                            <div class="font-medium text-gray-900">{{ number_format($sal->base_salary ?? 0, 2) }}</div>
                            <div class="text-gray-400">From {{ $sal->effective_from?->format('M d, Y') }} · {{ ucfirst($sal->pay_frequency ?? '') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No salary records</p>
                @endforelse
            </div>
            <details class="mt-3">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Add Salary Record</summary>
                <form method="POST" action="{{ route('employees.salaries.store', $employee) }}" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <input type="date" name="effective_from" placeholder="Effective From" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <input type="number" step="0.01" name="base_salary" placeholder="Base Salary" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <select name="pay_frequency" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                        @foreach(['monthly','biweekly','weekly'] as $pf)
                            <option value="{{ $pf }}">{{ ucfirst($pf) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                </form>
            </details>
        </div>

        {{-- Bank Accounts --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Bank Accounts</h3>
            <div class="space-y-2">
                @forelse($employee->bankAccounts as $ba)
                    <div class="text-xs border border-gray-100 rounded-lg p-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900">{{ $ba->bank_name }}</span>
                            @if($ba->is_primary)<span class="text-[9px] text-copper-600 font-bold">PRIMARY</span>@endif
                        </div>
                        <div class="text-gray-400 mt-0.5">{{ $ba->account_name }} · {{ $ba->account_number }}</div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No bank accounts</p>
                @endforelse
            </div>
            <details class="mt-3">
                <summary class="text-xs font-medium text-navy-600 cursor-pointer hover:text-navy-700">+ Add Bank Account</summary>
                <form method="POST" action="{{ route('employees.bank-accounts.store', $employee) }}" class="mt-3 space-y-2" data-ajax data-reset-on-success="true">
                    @csrf
                    <input type="text" name="bank_name" placeholder="Bank Name" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <input type="text" name="account_name" placeholder="Account Name" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <input type="text" name="account_number" placeholder="Account Number" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg outline-none" required>
                    <label class="flex items-center gap-1.5 text-xs text-gray-600"><input type="checkbox" name="is_primary" value="1"> Set as primary</label>
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700">Add</button>
                </form>
            </details>
        </div>
    </div>

    {{-- Right: Attendance, Leave, Payslips --}}
    <div class="space-y-4">
        {{-- Recent Attendance --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Attendance</h3>
            <div class="space-y-1.5">
                @forelse($employee->attendanceRecords as $rec)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-600">{{ $rec->date?->format('M d') }}</span>
                        @php $stColors = ['present'=>'green','late'=>'amber','absent'=>'red','half_day'=>'sky','on_leave'=>'purple']; @endphp
                        @php $color = $stColors[$rec->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst($rec->status) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No attendance records</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Leave Requests --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Leave Requests</h3>
            <div class="space-y-1.5">
                @forelse($employee->leaveRequests as $lr)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-600">{{ $lr->start_date?->format('M d') }} - {{ $lr->end_date?->format('M d') }}</span>
                        @php $lrColors = ['pending'=>'amber','approved'=>'green','rejected'=>'red','cancelled'=>'gray']; @endphp
                        @php $color = $lrColors[$lr->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-{{ $color }}-50 text-{{ $color }}-700">{{ ucfirst($lr->status) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No leave requests</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Payslips --}}
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Payslips</h3>
            <div class="space-y-1.5">
                @forelse($employee->payslips as $ps)
                    <a href="{{ route('payroll.payslips.show', $ps) }}" class="flex items-center justify-between text-xs hover:bg-gray-50 -mx-1 px-1 py-0.5 rounded transition-colors">
                        <span class="text-gray-600">{{ $ps->payrollRun?->period_name ?? 'N/A' }}</span>
                        <span class="font-medium text-gray-900">{{ number_format($ps->net_pay ?? 0, 0) }}</span>
                    </a>
                @empty
                    <p class="text-xs text-gray-400">No payslips</p>
                @endforelse
            </div>
        </div>

        @if($employee->notes)
        <div class="bg-white rounded-xl border p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Notes</h3>
            <p class="text-xs text-gray-600">{{ $employee->notes }}</p>
        </div>
        @endif
    </div>
</div>

@endsection

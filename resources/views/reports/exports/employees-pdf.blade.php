<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Employee Report', 'docCode' => 'EMP'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Employee Report',
    'subtitle' => 'Reporting Period: ' . \Carbon\Carbon::parse($from)->format('d M Y') . ' — ' . \Carbon\Carbon::parse($to)->format('d M Y'),
    'docCode' => 'EMP',
])

<div class="summary">
    <div class="card"><div class="label">Total Employees</div><div class="value">{{ $total }}</div></div>
    <div class="card"><div class="label">Active</div><div class="value" style="color:#16a34a">{{ $active }}</div></div>
    <div class="card"><div class="label">On Probation</div><div class="value" style="color:#d97706">{{ $onProbation }}</div></div>
    <div class="card"><div class="label">Terminated</div><div class="value" style="color:#dc2626">{{ $terminated }}</div></div>
</div>

<h2 class="section-title">1. Headcount by Department</h2>
<table class="data-table">
<tr><th>Department</th><th style="text-align:right">Employees</th></tr>
@foreach($byDept as $dept => $count)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $dept }}</td><td style="text-align:right">{{ $count }}</td></tr>
@endforeach
</table>

<h2 class="section-title">2. Headcount by Employment Type</h2>
<table class="data-table">
<tr><th>Employment Type</th><th style="text-align:right">Employees</th></tr>
@foreach($byType as $type => $count)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ ucfirst(str_replace('_',' ',$type)) }}</td><td style="text-align:right">{{ $count }}</td></tr>
@endforeach
</table>

<h2 class="section-title">3. New Joiners ({{ $joiners->count() }})</h2>
<table class="data-table">
<tr><th>Employee Code</th><th>Full Name</th><th>Hire Date</th></tr>
@forelse($joiners as $j)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $j->employee_code }}</td><td>{{ $j->first_name }} {{ $j->last_name }}</td><td>{{ $j->hire_date?->format('d M Y') }}</td></tr>
@empty
<tr><td colspan="3" style="text-align:center;color:#8B93A3">No new joiners in this period</td></tr>
@endforelse
</table>

<h2 class="section-title">4. Leavers ({{ $leavers->count() }})</h2>
<table class="data-table">
<tr><th>Employee Code</th><th>Full Name</th><th>Status</th></tr>
@forelse($leavers as $l)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $l->employee_code }}</td><td>{{ $l->first_name }} {{ $l->last_name }}</td><td>{{ ucfirst(str_replace('_',' ',$l->employment_status)) }}</td></tr>
@empty
<tr><td colspan="3" style="text-align:center;color:#8B93A3">No leavers in this period</td></tr>
@endforelse
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

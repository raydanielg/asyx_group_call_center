<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 20px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
.summary { display: flex; gap: 15px; margin-bottom: 20px; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; flex: 1; text-align: center; }
.card .label { font-size: 9px; text-transform: uppercase; color: #888; }
.card .value { font-size: 22px; font-weight: bold; margin-top: 4px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
h2 { color: #0D3E63; font-size: 14px; margin: 15px 0 8px; }
.bar-container { background: #f0f0f0; border-radius: 4px; height: 18px; overflow: hidden; }
.bar-fill { height: 100%; background: #0D3E63; border-radius: 4px; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Employee Report</h1>
    <p>Period: {{ $from }} to {{ $to }} | Generated: {{ now()->format('M d, Y H:i') }}</p>
</div>

<div class="summary">
    <div class="card"><div class="label">Total</div><div class="value">{{ $total }}</div></div>
    <div class="card"><div class="label">Active</div><div class="value" style="color:#16a34a">{{ $active }}</div></div>
    <div class="card"><div class="label">Probation</div><div class="value" style="color:#d97706">{{ $onProbation }}</div></div>
    <div class="card"><div class="label">Terminated</div><div class="value" style="color:#dc2626">{{ $terminated }}</div></div>
</div>

<h2>By Department</h2>
<table>
<tr><th>Department</th><th>Count</th></tr>
@foreach($byDept as $dept => $count)
<tr><td>{{ $dept }}</td><td>{{ $count }}</td></tr>
@endforeach
</table>

<h2>By Employment Type</h2>
<table>
<tr><th>Type</th><th>Count</th></tr>
@foreach($byType as $type => $count)
<tr><td>{{ ucfirst(str_replace('_',' ',$type)) }}</td><td>{{ $count }}</td></tr>
@endforeach
</table>

<h2>New Joiners ({{ $joiners->count() }})</h2>
<table>
<tr><th>Code</th><th>Name</th><th>Hire Date</th></tr>
@foreach($joiners as $j)
<tr><td>{{ $j->employee_code }}</td><td>{{ $j->first_name }} {{ $j->last_name }}</td><td>{{ $j->hire_date?->format('M d, Y') }}</td></tr>
@endforeach
</table>

<h2>Leavers ({{ $leavers->count() }})</h2>
<table>
<tr><th>Code</th><th>Name</th><th>Status</th></tr>
@foreach($leavers as $l)
<tr><td>{{ $l->employee_code }}</td><td>{{ $l->first_name }} {{ $l->last_name }}</td><td>{{ ucfirst(str_replace('_',' ',$l->employment_status)) }}</td></tr>
@endforeach
</table>
</body>
</html>

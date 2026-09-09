<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Attendance Report', 'docCode' => 'ATT'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Attendance Report',
    'subtitle' => 'Reporting Period: ' . \Carbon\Carbon::parse($from)->format('d M Y') . ' — ' . \Carbon\Carbon::parse($to)->format('d M Y'),
    'docCode' => 'ATT',
])

<div class="summary">
    <div class="card"><div class="label">Present</div><div class="value" style="color:#16a34a">{{ $summary['present'] }}</div></div>
    <div class="card"><div class="label">Late</div><div class="value" style="color:#d97706">{{ $summary['late'] }}</div></div>
    <div class="card"><div class="label">Absent</div><div class="value" style="color:#dc2626">{{ $summary['absent'] }}</div></div>
    <div class="card"><div class="label">Half Day</div><div class="value" style="color:#0284c7">{{ $summary['half_day'] }}</div></div>
    <div class="card"><div class="label">On Leave</div><div class="value" style="color:#632871">{{ $summary['on_leave'] }}</div></div>
    <div class="card"><div class="label">Overtime (hrs)</div><div class="value" style="color:#A56035">{{ number_format($summary['total_ot'] / 60, 1) }}</div></div>
</div>

<h2 class="section-title">Attendance by Employee</h2>
<table class="data-table">
<tr>
    <th>Employee</th>
    <th style="text-align:right">Present</th>
    <th style="text-align:right">Late</th>
    <th style="text-align:right">Absent</th>
    <th style="text-align:right">Overtime (min)</th>
</tr>
@forelse($byEmployee as $row)
<tr class="{{ $loop->even ? 'row-alt' : '' }}">
    <td>{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
    <td style="text-align:right">{{ $row['present'] }}</td>
    <td style="text-align:right">{{ $row['late'] }}</td>
    <td style="text-align:right">{{ $row['absent'] }}</td>
    <td style="text-align:right">{{ $row['ot_minutes'] }}</td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;color:#8B93A3">No attendance records in this period</td></tr>
@endforelse
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

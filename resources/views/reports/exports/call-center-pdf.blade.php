<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Call Center Report', 'docCode' => 'CC'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Call Center Report',
    'subtitle' => 'Reporting Period: ' . \Carbon\Carbon::parse($from)->format('d M Y') . ' — ' . \Carbon\Carbon::parse($to)->format('d M Y'),
    'docCode' => 'CC',
])

<div class="summary">
    <div class="card"><div class="label">Total Calls</div><div class="value">{{ $summary['total_calls'] }}</div></div>
    <div class="card"><div class="label">Answered</div><div class="value" style="color:#16a34a">{{ $summary['answered'] }}</div></div>
    <div class="card"><div class="label">Missed</div><div class="value" style="color:#dc2626">{{ $summary['missed'] }}</div></div>
    <div class="card"><div class="label">Conversions</div><div class="value" style="color:#A56035">{{ $summary['conversions'] }}</div></div>
    <div class="card"><div class="label">Avg AHT (s)</div><div class="value">{{ $summary['avg_aht'] }}</div></div>
    <div class="card"><div class="label">Avg CSAT</div><div class="value" style="color:#632871">{{ $summary['avg_csat'] }}</div></div>
</div>

<h2 class="section-title">Performance by Agent</h2>
<table class="data-table">
<tr>
    <th>Agent</th>
    <th style="text-align:right">Calls</th>
    <th style="text-align:right">AHT (s)</th>
    <th style="text-align:right">CSAT</th>
    <th style="text-align:right">Conversions</th>
</tr>
@forelse($byEmployee as $row)
<tr class="{{ $loop->even ? 'row-alt' : '' }}">
    <td>{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
    <td style="text-align:right">{{ $row['calls'] }}</td>
    <td style="text-align:right">{{ $row['aht'] }}</td>
    <td style="text-align:right">{{ $row['csat'] }}</td>
    <td style="text-align:right">{{ $row['conversions'] }}</td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;color:#8B93A3">No call center data in this period</td></tr>
@endforelse
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

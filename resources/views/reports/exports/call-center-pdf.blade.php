<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 20px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
.summary { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 10px; flex: 1; text-align: center; min-width: 100px; }
.card .label { font-size: 9px; text-transform: uppercase; color: #888; }
.card .value { font-size: 18px; font-weight: bold; margin-top: 4px; }
table { width: 100%; border-collapse: collapse; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Call Center Report</h1>
    <p>Period: {{ $from }} to {{ $to }} | Generated: {{ now()->format('M d, Y H:i') }}</p>
</div>

<div class="summary">
    <div class="card"><div class="label">Total Calls</div><div class="value">{{ $summary['total_calls'] }}</div></div>
    <div class="card"><div class="label">Answered</div><div class="value" style="color:#16a34a">{{ $summary['answered'] }}</div></div>
    <div class="card"><div class="label">Missed</div><div class="value" style="color:#dc2626">{{ $summary['missed'] }}</div></div>
    <div class="card"><div class="label">Conversions</div><div class="value" style="color:#A56035">{{ $summary['conversions'] }}</div></div>
    <div class="card"><div class="label">Avg AHT (s)</div><div class="value">{{ $summary['avg_aht'] }}</div></div>
    <div class="card"><div class="label">Avg CSAT</div><div class="value" style="color:#632871">{{ $summary['avg_csat'] }}</div></div>
</div>

<table>
<tr>
    <th>Employee</th>
    <th style="text-align:right">Calls</th>
    <th style="text-align:right">AHT (s)</th>
    <th style="text-align:right">CSAT</th>
    <th style="text-align:right">Conversions</th>
</tr>
@foreach($byEmployee as $row)
<tr>
    <td>{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
    <td style="text-align:right">{{ $row['calls'] }}</td>
    <td style="text-align:right">{{ $row['aht'] }}</td>
    <td style="text-align:right">{{ $row['csat'] }}</td>
    <td style="text-align:right">{{ $row['conversions'] }}</td>
</tr>
@endforeach
</table>
</body>
</html>

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
.card .value { font-size: 18px; font-weight: bold; margin-top: 4px; }
table { width: 100%; border-collapse: collapse; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Payroll Report</h1>
    <p>Year: {{ $year }} | Generated: {{ now()->format('M d, Y H:i') }}</p>
</div>

<div class="summary">
    <div class="card"><div class="label">Total Gross</div><div class="value">{{ number_format($totalGross, 0) }}</div></div>
    <div class="card"><div class="label">Total Deductions</div><div class="value" style="color:#dc2626">{{ number_format($totalDeductions, 0) }}</div></div>
    <div class="card"><div class="label">Total Net</div><div class="value" style="color:#16a34a">{{ number_format($totalNet, 0) }}</div></div>
</div>

<table>
<tr>
    <th>Month</th>
    <th style="text-align:right">Employees</th>
    <th style="text-align:right">Gross</th>
    <th style="text-align:right">Deductions</th>
    <th style="text-align:right">Net</th>
</tr>
@foreach($monthlyData as $m)
<tr>
    <td>{{ $m['month'] }}</td>
    <td style="text-align:right">{{ $m['employees'] }}</td>
    <td style="text-align:right">{{ number_format($m['gross'], 0) }}</td>
    <td style="text-align:right">{{ number_format($m['deductions'], 0) }}</td>
    <td style="text-align:right;font-weight:bold">{{ number_format($m['net'], 0) }}</td>
</tr>
@endforeach
</table>
</body>
</html>

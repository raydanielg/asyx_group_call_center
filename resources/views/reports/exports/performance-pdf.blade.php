<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 20px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
h2 { color: #0D3E63; font-size: 14px; margin: 15px 0 8px; }
.stat { display: inline-block; border: 1px solid #ddd; border-radius: 8px; padding: 10px 20px; margin-right: 10px; }
.stat .label { font-size: 9px; text-transform: uppercase; color: #888; }
.stat .value { font-size: 18px; font-weight: bold; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Performance Report</h1>
    <p>Period: {{ date('F Y', mktime(0,0,0,$month,1,$year)) }} | Generated: {{ now()->format('M d, Y H:i') }}</p>
</div>

<div style="margin-bottom:20px">
    <div class="stat"><div class="label">Avg Score</div><div class="value">{{ round($avgScore ?? 0, 1) }}</div></div>
    <div class="stat"><div class="label">Total Evaluations</div><div class="value">{{ array_sum($gradeDist) }}</div></div>
</div>

<h2>Grade Distribution</h2>
<table>
<tr><th>Grade</th><th>Count</th></tr>
@foreach($gradeDist as $grade => $count)
<tr><td>{{ $grade }}</td><td>{{ $count }}</td></tr>
@endforeach
</table>

<h2>Top Performers</h2>
<table>
<tr><th>Rank</th><th>Employee</th><th>Grade</th><th>Score</th></tr>
@foreach($topPerformers as $i => $eval)
<tr><td>{{ $i + 1 }}</td><td>{{ $eval->employee?->first_name ?? '' }} {{ $eval->employee?->last_name ?? '' }}</td><td>{{ $eval->grade }}</td><td>{{ round($eval->weighted_score, 1) }}</td></tr>
@endforeach
</table>
</body>
</html>

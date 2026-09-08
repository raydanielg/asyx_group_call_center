<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 20px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
.summary { display: flex; gap: 10px; margin-bottom: 20px; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 10px; flex: 1; text-align: center; }
.card .label { font-size: 9px; text-transform: uppercase; color: #888; }
.card .value { font-size: 18px; font-weight: bold; margin-top: 4px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
h2 { color: #0D3E63; font-size: 14px; margin: 15px 0 8px; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Recruitment Report</h1>
    <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
</div>

<div class="summary">
    <div class="card"><div class="label">Total Applicants</div><div class="value">{{ $totalApplicants }}</div></div>
    <div class="card"><div class="label">Hired</div><div class="value" style="color:#16a34a">{{ $hired }}</div></div>
    <div class="card"><div class="label">Rejected</div><div class="value" style="color:#dc2626">{{ $rejected }}</div></div>
    <div class="card"><div class="label">In Progress</div><div class="value" style="color:#d97706">{{ $inProgress }}</div></div>
    <div class="card"><div class="label">Conversion Rate</div><div class="value" style="color:#0D3E63">{{ $conversionRate }}%</div></div>
</div>

<h2>Job Positions</h2>
<table>
<tr><th>Title</th><th>Openings</th><th>Applicants</th><th>Status</th></tr>
@foreach($jobs as $job)
<tr><td>{{ $job->title }}</td><td>{{ $job->openings }}</td><td>{{ $job->applicants_count }}</td><td>{{ ucfirst($job->status) }}</td></tr>
@endforeach
</table>

<h2>By Source</h2>
<table>
<tr><th>Source</th><th>Count</th></tr>
@foreach($bySource as $source => $count)
<tr><td>{{ $source ?? 'Unknown' }}</td><td>{{ $count }}</td></tr>
@endforeach
</table>

<h2>By Stage</h2>
<table>
<tr><th>Stage</th><th>Count</th></tr>
@foreach($byStage as $stage => $count)
<tr><td>{{ ucfirst(str_replace('_',' ',$stage)) }}</td><td>{{ $count }}</td></tr>
@endforeach
</table>
</body>
</html>

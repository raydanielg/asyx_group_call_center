<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Recruitment Report', 'docCode' => 'REC'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Recruitment Report',
    'subtitle' => 'As of ' . now()->format('d M Y'),
    'docCode' => 'REC',
])

<div class="summary">
    <div class="card"><div class="label">Total Applicants</div><div class="value">{{ $totalApplicants }}</div></div>
    <div class="card"><div class="label">Hired</div><div class="value" style="color:#16a34a">{{ $hired }}</div></div>
    <div class="card"><div class="label">Rejected</div><div class="value" style="color:#dc2626">{{ $rejected }}</div></div>
    <div class="card"><div class="label">In Progress</div><div class="value" style="color:#d97706">{{ $inProgress }}</div></div>
    <div class="card"><div class="label">Conversion Rate</div><div class="value" style="color:#0D3E63">{{ $conversionRate }}%</div></div>
</div>

<h2 class="section-title">1. Open Job Positions</h2>
<table class="data-table">
<tr><th>Job Title</th><th style="text-align:right">Openings</th><th style="text-align:right">Applicants</th><th>Status</th></tr>
@forelse($jobs as $job)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $job->title }}</td><td style="text-align:right">{{ $job->openings }}</td><td style="text-align:right">{{ $job->applicants_count }}</td><td>{{ ucfirst($job->status) }}</td></tr>
@empty
<tr><td colspan="4" style="text-align:center;color:#8B93A3">No job positions found</td></tr>
@endforelse
</table>

<h2 class="section-title">2. Applicants by Source</h2>
<table class="data-table">
<tr><th>Source</th><th style="text-align:right">Applicants</th></tr>
@foreach($bySource as $source => $count)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $source ?? 'Unknown' }}</td><td style="text-align:right">{{ $count }}</td></tr>
@endforeach
</table>

<h2 class="section-title">3. Applicants by Pipeline Stage</h2>
<table class="data-table">
<tr><th>Stage</th><th style="text-align:right">Applicants</th></tr>
@foreach($byStage as $stage => $count)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ ucfirst(str_replace('_',' ',$stage)) }}</td><td style="text-align:right">{{ $count }}</td></tr>
@endforeach
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

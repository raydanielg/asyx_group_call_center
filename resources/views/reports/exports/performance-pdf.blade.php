<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Performance Report', 'docCode' => 'PERF'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Performance Report',
    'subtitle' => 'Period: ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)),
    'docCode' => 'PERF',
])

<div style="margin-bottom:16px">
    <div class="stat"><div class="label">Average Score</div><div class="value">{{ round($avgScore ?? 0, 1) }}</div></div>
    <div class="stat"><div class="label">Total Evaluations</div><div class="value">{{ array_sum($gradeDist) }}</div></div>
</div>

<h2 class="section-title">1. Grade Distribution</h2>
<table class="data-table">
<tr><th>Grade</th><th style="text-align:right">Employees</th></tr>
@foreach($gradeDist as $grade => $count)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $grade }}</td><td style="text-align:right">{{ $count }}</td></tr>
@endforeach
</table>

<h2 class="section-title">2. Top Performers</h2>
<table class="data-table">
<tr><th style="width:40px">Rank</th><th>Employee</th><th>Grade</th><th style="text-align:right">Score</th></tr>
@forelse($topPerformers as $i => $eval)
<tr class="{{ $loop->even ? 'row-alt' : '' }}"><td>{{ $i + 1 }}</td><td>{{ $eval->employee?->first_name ?? '' }} {{ $eval->employee?->last_name ?? '' }}</td><td>{{ $eval->grade }}</td><td style="text-align:right">{{ round($eval->weighted_score, 1) }}</td></tr>
@empty
<tr><td colspan="4" style="text-align:center;color:#8B93A3">No evaluations for this period</td></tr>
@endforelse
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

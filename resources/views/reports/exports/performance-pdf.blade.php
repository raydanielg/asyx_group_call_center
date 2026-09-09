<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 30px; line-height: 1.5; }
.header { border-bottom: 2px solid #0D3E63; padding-bottom: 12px; margin-bottom: 20px; }
.header .title { font-size: 18px; font-weight: 700; color: #0D3E63; }
.header .meta { font-size: 10px; color: #718096; margin-top: 4px; }
.section { margin-bottom: 22px; }
.section-title { font-size: 12px; font-weight: 700; color: #0D3E63; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; }
table { width: 100%; border-collapse: collapse; }
th { background: #0D3E63; color: #fff; padding: 7px 10px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
td { padding: 6px 10px; border-bottom: 1px solid #edf2f7; font-size: 10px; }
tr:nth-child(even) td { background: #f7fafc; }
.text-right { text-align: right; }
.footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #a0aec0; text-align: center; }
</style>
</head>
<body>

<div class="header">
    <div class="title">AYS Call Center &mdash; Performance Report</div>
    <div class="meta">Period: {{ date('F Y', mktime(0,0,0,$month,1,$year)) }} | Generated: {{ now()->format('M d, Y H:i') }}</div>
</div>

<div class="section">
    <div class="section-title">Grade Distribution</div>
    <table>
        <tr><th>Grade</th><th class="text-right">Count</th></tr>
        @foreach($gradeDist as $grade => $count)
        <tr><td>{{ $grade }}</td><td class="text-right">{{ $count }}</td></tr>
        @endforeach
    </table>
</div>

<div class="section">
    <div class="section-title">Top Performers</div>
    <table>
        <tr><th>Rank</th><th>Employee</th><th>Grade</th><th class="text-right">Score</th></tr>
        @foreach($topPerformers as $i => $eval)
        <tr><td>{{ $i + 1 }}</td><td>{{ $eval->employee?->first_name ?? '' }} {{ $eval->employee?->last_name ?? '' }}</td><td>{{ $eval->grade }}</td><td class="text-right">{{ round($eval->weighted_score, 1) }}</td></tr>
        @endforeach
    </table>
</div>

<div class="footer">AYS Call Center HRMS &mdash; Confidential</div>
</body>
</html>

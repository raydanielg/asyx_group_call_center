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
    <div class="title">AYS Call Center &mdash; Attendance Report</div>
    <div class="meta">Period: {{ $from }} to {{ $to }} | Generated: {{ now()->format('M d, Y H:i') }}</div>
</div>

<div class="section">
    <div class="section-title">Summary</div>
    <table>
        <tr><th>Present</th><th>Late</th><th>Absent</th><th>Half Day</th><th>On Leave</th><th>OT (hrs)</th></tr>
        <tr>
            <td>{{ $summary['present'] }}</td>
            <td>{{ $summary['late'] }}</td>
            <td>{{ $summary['absent'] }}</td>
            <td>{{ $summary['half_day'] }}</td>
            <td>{{ $summary['on_leave'] }}</td>
            <td>{{ number_format($summary['total_ot'] / 60, 1) }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">By Employee</div>
    <table>
        <tr>
            <th>Employee</th>
            <th class="text-right">Present</th>
            <th class="text-right">Late</th>
            <th class="text-right">Absent</th>
            <th class="text-right">OT (min)</th>
        </tr>
        @foreach($byEmployee as $row)
        <tr>
            <td>{{ $row['employee']?->first_name ?? '' }} {{ $row['employee']?->last_name ?? '' }}</td>
            <td class="text-right">{{ $row['present'] }}</td>
            <td class="text-right">{{ $row['late'] }}</td>
            <td class="text-right">{{ $row['absent'] }}</td>
            <td class="text-right">{{ $row['ot_minutes'] }}</td>
        </tr>
        @endforeach
    </table>
</div>

<div class="footer">AYS Call Center HRMS &mdash; Confidential</div>
</body>
</html>

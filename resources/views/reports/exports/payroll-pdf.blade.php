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
    <div class="title">AYS Call Center &mdash; Payroll Report</div>
    <div class="meta">Year: {{ $year }} | Generated: {{ now()->format('M d, Y H:i') }}</div>
</div>

<div class="section">
    <div class="section-title">Monthly Breakdown</div>
    <table>
        <tr>
            <th>Month</th>
            <th class="text-right">Employees</th>
            <th class="text-right">Gross</th>
            <th class="text-right">Deductions</th>
            <th class="text-right">Net</th>
        </tr>
        @foreach($monthlyData as $m)
        <tr>
            <td>{{ $m['month'] }}</td>
            <td class="text-right">{{ $m['employees'] }}</td>
            <td class="text-right">{{ number_format($m['gross'], 0) }}</td>
            <td class="text-right">{{ number_format($m['deductions'], 0) }}</td>
            <td class="text-right">{{ number_format($m['net'], 0) }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight:700">Total</td>
            <td class="text-right"></td>
            <td class="text-right" style="font-weight:700">{{ number_format($totalGross, 0) }}</td>
            <td class="text-right" style="font-weight:700">{{ number_format($totalDeductions, 0) }}</td>
            <td class="text-right" style="font-weight:700">{{ number_format($totalNet, 0) }}</td>
        </tr>
    </table>
</div>

<div class="footer">AYS Call Center HRMS &mdash; Confidential</div>
</body>
</html>

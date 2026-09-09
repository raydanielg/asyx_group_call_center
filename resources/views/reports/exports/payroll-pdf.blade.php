<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Payroll Report', 'docCode' => 'PAY'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Payroll Report',
    'subtitle' => 'Financial Year ' . $year,
    'docCode' => 'PAY',
])

<div class="summary">
    <div class="card"><div class="label">Total Gross</div><div class="value">{{ number_format($totalGross, 0) }}</div></div>
    <div class="card"><div class="label">Total Deductions</div><div class="value" style="color:#dc2626">{{ number_format($totalDeductions, 0) }}</div></div>
    <div class="card"><div class="label">Total Net</div><div class="value" style="color:#16a34a">{{ number_format($totalNet, 0) }}</div></div>
</div>

<h2 class="section-title">Monthly Payroll Breakdown</h2>
<table class="data-table">
<tr>
    <th>Month</th>
    <th style="text-align:right">Employees</th>
    <th style="text-align:right">Gross</th>
    <th style="text-align:right">Deductions</th>
    <th style="text-align:right">Net Pay</th>
</tr>
@foreach($monthlyData as $m)
<tr class="{{ $loop->even ? 'row-alt' : '' }}">
    <td>{{ $m['month'] }}</td>
    <td style="text-align:right">{{ $m['employees'] }}</td>
    <td style="text-align:right">{{ number_format($m['gross'], 0) }}</td>
    <td style="text-align:right">{{ number_format($m['deductions'], 0) }}</td>
    <td style="text-align:right;font-weight:bold">{{ number_format($m['net'], 0) }}</td>
</tr>
@endforeach
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

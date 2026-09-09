<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'System Audit Report', 'docCode' => 'SYS'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'System Audit Report',
    'subtitle' => 'Full compliance and system integrity review',
    'docCode' => 'SYS',
])

@foreach($sections as $section)
<h2 class="section-title">{{ $loop->iteration }}. {{ $section['title'] }}</h2>
<table class="data-table">
<tr><th>Item</th><th style="width:70px">Status</th><th>Details</th></tr>
@foreach($section['items'] as $item)
<tr class="{{ $loop->even ? 'row-alt' : '' }}">
    <td>{{ $item['name'] }}</td>
    <td class="badge-{{ strtolower($item['status']) === 'pass' ? 'pass' : (strtolower($item['status']) === 'info' ? 'info' : 'fail') }}">{{ $item['status'] }}</td>
    <td>{{ $item['details'] }}</td>
</tr>
@endforeach
</table>
@endforeach

@include('reports.exports.partials.signoff')

</body>
</html>

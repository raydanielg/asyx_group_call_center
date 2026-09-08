<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 22px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
h2 { color: #0D3E63; font-size: 15px; margin: 20px 0 10px; border-bottom: 1px solid #e0e3f0; padding-bottom: 5px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
th { background: #0D3E63; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
.pass { color: #16a34a; font-weight: bold; }
.info { color: #0284c7; font-weight: bold; }
.fail { color: #dc2626; font-weight: bold; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — System Audit Report</h1>
    <p>Generated: {{ $generated_at }}</p>
</div>

@foreach($sections as $section)
<h2>{{ $section['title'] }}</h2>
<table>
<tr><th>Item</th><th>Status</th><th>Details</th></tr>
@foreach($section['items'] as $item)
<tr>
    <td>{{ $item['name'] }}</td>
    <td class="{{ strtolower($item['status']) }}">{{ $item['status'] }}</td>
    <td>{{ $item['details'] }}</td>
</tr>
@endforeach
</table>
@endforeach
</body>
</html>

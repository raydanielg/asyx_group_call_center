<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 11px; color: #1A2332; margin: 20px; }
.header { text-align: center; border-bottom: 3px solid #0D3E63; padding-bottom: 10px; margin-bottom: 20px; }
.header h1 { color: #0D3E63; font-size: 20px; margin: 0; }
.header p { color: #666; font-size: 11px; margin: 4px 0 0; }
table { width: 100%; border-collapse: collapse; }
th { background: #0D3E63; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
</style>
</head>
<body>
<div class="header">
    <h1>AYS Call Center — Audit Log Report</h1>
    <p>Generated: {{ now()->format('M d, Y H:i') }} | Showing {{ $logs->count() }} entries</p>
</div>

<table>
<tr>
    <th>Timestamp</th>
    <th>User</th>
    <th>Action</th>
    <th>Model</th>
    <th>IP Address</th>
</tr>
@foreach($logs as $log)
<tr>
    <td>{{ $log->occurred_at?->format('Y-m-d H:i:s') }}</td>
    <td>{{ $log->user?->name ?? 'System' }}</td>
    <td>{{ $log->action }}</td>
    <td>{{ $log->auditable_type ? class_basename($log->auditable_type) . '#' . $log->auditable_id : '-' }}</td>
    <td>{{ $log->ip_address ?? '-' }}</td>
</tr>
@endforeach
</table>
</body>
</html>

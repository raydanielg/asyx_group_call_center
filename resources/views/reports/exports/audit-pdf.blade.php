<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #2d3748; margin: 30px; line-height: 1.5; }
.header { border-bottom: 2px solid #0D3E63; padding-bottom: 12px; margin-bottom: 20px; }
.header .title { font-size: 18px; font-weight: 700; color: #0D3E63; }
.header .meta { font-size: 10px; color: #718096; margin-top: 4px; }
.section { margin-bottom: 22px; }
.section-title { font-size: 12px; font-weight: 700; color: #0D3E63; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; }
table { width: 100%; border-collapse: collapse; }
th { background: #0D3E63; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
td { padding: 5px 8px; border-bottom: 1px solid #edf2f7; font-size: 9px; }
tr:nth-child(even) td { background: #f7fafc; }
.footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #a0aec0; text-align: center; }
</style>
</head>
<body>

<div class="header">
    <div class="title">AYS Call Center &mdash; Audit Log Report</div>
    <div class="meta">Generated: {{ now()->format('M d, Y H:i') }} | {{ $logs->count() }} entries</div>
</div>

<div class="section">
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
</div>

<div class="footer">AYS Call Center HRMS &mdash; Confidential</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'Audit Log Report', 'docCode' => 'AUD'])
</head>
<body>

@include('reports.exports.partials.letterhead', [
    'title' => 'Audit Log Report',
    'subtitle' => 'Showing ' . $logs->count() . ' log ' . \Illuminate\Support\Str::plural('entry', $logs->count()),
    'docCode' => 'AUD',
])

<table class="data-table">
<tr>
    <th>Timestamp</th>
    <th>User</th>
    <th>Action</th>
    <th>Model / Record</th>
    <th>IP Address</th>
</tr>
@forelse($logs as $log)
<tr class="{{ $loop->even ? 'row-alt' : '' }}">
    <td>{{ $log->occurred_at?->format('Y-m-d H:i:s') }}</td>
    <td>{{ $log->user?->name ?? 'System' }}</td>
    <td>{{ $log->action }}</td>
    <td>{{ $log->auditable_type ? class_basename($log->auditable_type) . '#' . $log->auditable_id : '-' }}</td>
    <td>{{ $log->ip_address ?? '-' }}</td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;color:#8B93A3">No audit log entries match this filter</td></tr>
@endforelse
</table>

@include('reports.exports.partials.signoff')

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
@include('reports.exports.partials.style', ['docTitle' => 'User Guide', 'docCode' => 'GDE'])
<style>
    .guide-body { line-height: 1.55; }
    h1.guide-h1 { color: #0D3E63; font-size: 15px; margin: 22px 0 8px; border-bottom: 2px solid #A56035; padding-bottom: 5px; }
    h2.guide-h2 { color: #A56035; font-size: 13px; margin: 16px 0 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    h3.guide-h3 { color: #0D3E63; font-size: 11px; margin: 12px 0 4px; }
    .role { border: 1px solid #E4E7EC; border-top: 3px solid #0D3E63; border-radius: 4px; padding: 14px 18px; margin-bottom: 16px; page-break-inside: avoid; }
    .role-header { margin-bottom: 8px; }
    .role-badge { background: #0D3E63; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 9.5px; font-weight: bold; letter-spacing: 0.5px; }
    .permissions { background: #F7F8FA; border-radius: 4px; padding: 8px 14px; margin: 8px 0; }
    .permissions ul { margin: 4px 0; padding-left: 18px; }
    .permissions li { font-size: 9.5px; color: #555; margin: 2px 0; }
    ol.guide-steps { margin: 4px 0; padding-left: 22px; }
    ol.guide-steps li { font-size: 9.5px; color: #444; margin: 3px 0; }
    .toc-box { background: #F7F8FA; border: 1px solid #E4E7EC; border-radius: 4px; padding: 12px 20px; margin-bottom: 18px; }
    .toc-box h2 { margin-top: 0; }
    .toc-box ol { margin: 4px 0; padding-left: 20px; }
    .toc-box ol li { font-size: 10px; margin: 5px 0; }
</style>
</head>
<body class="guide-body">

@include('reports.exports.partials.letterhead', [
    'title' => 'User Guide',
    'subtitle' => 'Step-by-Step Manual for All System Roles',
    'docCode' => 'GDE',
])

<div class="toc-box">
    <h2 class="section-title" style="margin-top:0">Table of Contents</h2>
    <ol>
        @foreach($roles as $role)
        <li>{{ $role['name'] }} &mdash; {{ $role['description'] }}</li>
        @endforeach
    </ol>
</div>

@foreach($roles as $role)
<div class="role">
    <div class="role-header">
        <span class="role-badge">{{ $role['name'] }}</span>
    </div>
    <p style="font-size:9.5px;color:#444;margin:4px 0 8px">{{ $role['description'] }}</p>

    <h3 class="guide-h3">Permissions</h3>
    <div class="permissions">
        <ul>
            @foreach($role['permissions'] as $perm)
            <li>{{ $perm }}</li>
            @endforeach
        </ul>
    </div>

    @foreach($role['modules'] as $module)
    <h3 class="guide-h3">{{ $module['name'] }}</h3>
    <ol class="guide-steps">
        @foreach($module['steps'] as $step)
        <li>{{ $step }}</li>
        @endforeach
    </ol>
    @endforeach
</div>
@endforeach

<div class="signoff-note" style="margin-top:12px">
    This user guide is a system-generated reference document for the AYS Call Center HRMS. For further assistance, contact your system administrator.
</div>

</body>
</html>

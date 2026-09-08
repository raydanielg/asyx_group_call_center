<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; font-size: 12px; color: #1A2332; margin: 25px; line-height: 1.6; }
.cover { text-align: center; padding: 60px 0; border-bottom: 3px solid #0D3E63; margin-bottom: 30px; }
.cover h1 { color: #0D3E63; font-size: 28px; margin: 0; }
.cover p { color: #666; font-size: 13px; margin: 8px 0 0; }
h1 { color: #0D3E63; font-size: 20px; margin: 25px 0 10px; border-bottom: 2px solid #A56035; padding-bottom: 5px; }
h2 { color: #A56035; font-size: 16px; margin: 20px 0 8px; }
h3 { color: #0D3E63; font-size: 13px; margin: 15px 0 5px; }
.role { border: 1px solid #ddd; border-radius: 10px; padding: 15px 20px; margin-bottom: 20px; page-break-inside: avoid; }
.role-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.role-badge { background: #0D3E63; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: bold; }
.permissions { background: #f8f9fb; border-radius: 8px; padding: 10px 15px; margin: 10px 0; }
.permissions ul { margin: 5px 0; padding-left: 20px; }
.permissions li { font-size: 11px; color: #555; margin: 3px 0; }
ol { margin: 5px 0; padding-left: 25px; }
ol li { font-size: 11px; color: #444; margin: 4px 0; }
.toc { background: #f8f9fb; border-radius: 10px; padding: 15px 25px; margin-bottom: 25px; }
.toc h2 { margin-top: 0; }
.toc ol li { font-size: 12px; margin: 6px 0; }
</style>
</head>
<body>

<div class="cover">
    <h1>AYS Call Center HRMS</h1>
    <p>User Guide &mdash; Step-by-Step Manual</p>
    <p>Generated: {{ $generated_at }}</p>
</div>

<div class="toc">
    <h2>Table of Contents</h2>
    <ol>
        @foreach($roles as $i => $role)
        <li>{{ $role['name'] }} &mdash; {{ $role['description'] }}</li>
        @endforeach
    </ol>
</div>

@foreach($roles as $role)
<div class="role">
    <div class="role-header">
        <span class="role-badge">{{ $role['name'] }}</span>
    </div>
    <p>{{ $role['description'] }}</p>

    <h3>Permissions</h3>
    <div class="permissions">
        <ul>
            @foreach($role['permissions'] as $perm)
            <li>{{ $perm }}</li>
            @endforeach
        </ul>
    </div>

    @foreach($role['modules'] as $module)
    <h3>{{ $module['name'] }}</h3>
    <ol>
        @foreach($module['steps'] as $step)
        <li>{{ $step }}</li>
        @endforeach
    </ol>
    @endforeach
</div>
@endforeach

</body>
</html>

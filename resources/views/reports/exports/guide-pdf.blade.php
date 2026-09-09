<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; margin: 30px; line-height: 1.6; }
.cover { text-align: center; padding: 50px 0; border-bottom: 2px solid #0D3E63; margin-bottom: 25px; }
.cover h1 { color: #0D3E63; font-size: 24px; font-weight: 700; }
.cover p { color: #718096; font-size: 12px; margin-top: 6px; }
h1 { color: #0D3E63; font-size: 16px; font-weight: 700; margin: 22px 0 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
h2 { color: #A56035; font-size: 13px; font-weight: 600; margin: 16px 0 6px; }
h3 { color: #0D3E63; font-size: 12px; font-weight: 600; margin: 12px 0 4px; }
.role { border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin-bottom: 18px; page-break-inside: avoid; }
.role-badge { background: #0D3E63; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; display: inline-block; margin-bottom: 8px; }
.permissions { background: #f7fafc; border-radius: 6px; padding: 8px 14px; margin: 8px 0; }
.permissions ul { margin: 4px 0; padding-left: 18px; }
.permissions li { font-size: 10px; color: #4a5568; margin: 2px 0; }
ol { margin: 4px 0; padding-left: 22px; }
ol li { font-size: 10px; color: #4a5568; margin: 3px 0; }
.toc { background: #f7fafc; border-radius: 8px; padding: 12px 22px; margin-bottom: 20px; }
.toc h2 { margin-top: 0; }
.toc ol li { font-size: 11px; margin: 4px 0; }
.footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #a0aec0; text-align: center; }
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
    <span class="role-badge">{{ $role['name'] }}</span>
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

<div class="footer">AYS Call Center HRMS &mdash; Confidential</div>
</body>
</html>

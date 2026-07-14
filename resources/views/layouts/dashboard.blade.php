<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AYS Call Center'))</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons8-logo-32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons8-logo-96.png') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 50:'#eef4f9',100:'#d4e3f0',200:'#a8c7e0',300:'#6a9bc8',400:'#3a6d9f',500:'#0D3E63',600:'#0a3556',700:'#082c49',800:'#06233c',900:'#041a2f' },
                        copper: { 50:'#faf3ee',100:'#f0ddd0',200:'#e0bba6',300:'#d09a7d',400:'#c07854',500:'#A56035',600:'#8f522d',700:'#794426',800:'#63361e',900:'#4d2817' },
                        purple: { 50:'#f5edf6',100:'#e8d4ec',200:'#d1a9d9',300:'#b97ec6',400:'#9d53b0',500:'#632871',600:'#552361',700:'#471e51',800:'#391940',900:'#2b142f' },
                        red: { 50:'#fef0f0',100:'#fcd5d6',200:'#faabad',300:'#f6888a',400:'#f26567',500:'#EC2226',600:'#c91d20',700:'#a6181a',800:'#831316',900:'#660e10' }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
        .animate-fade { animation: fadeIn 0.3s ease-out both; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); }
        .sidebar-link.active { background: rgba(255,255,255,0.08); color: #fff; }
        .sidebar-submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .sidebar-submenu.open { max-height: 500px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #06233c; }
        ::-webkit-scrollbar-thumb { background: #0D3E63; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #A56035; }
        .card-sm { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .card-sm:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(0,0,0,0.1); }
        .swal2-popup { font-family: 'Nunito', sans-serif !important; border-radius: 12px !important; }
        .swal2-toast { font-family: 'Nunito', sans-serif !important; border-radius: 10px !important; box-shadow: 0 4px 24px rgba(0,0,0,0.12) !important; }
        .swal2-icon { border-radius: 50% !important; }
        .swal2-title { font-size: 14px !important; font-weight: 700 !important; padding: 0 !important; }
        .swal2-html-container { font-size: 12px !important; margin: 0 !important; }
        .swal2-confirm { border-radius: 8px !important; font-weight: 700 !important; font-size: 12px !important; padding: 6px 16px !important; }
    </style>
</head>
<body class="font-['Nunito',sans-serif] antialiased bg-[#F8F9FB] text-[#1A2332]">

    {{-- Mobile Overlay --}}
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="dashSidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-navy-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
        {{-- Brand --}}
        <div class="h-16 flex items-center px-6 border-b border-navy-800/50 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div class="ml-2.5 leading-tight">
                <span class="text-white font-bold text-sm tracking-wide block">AYS Call</span>
                <span class="text-copper-400 text-[10px] font-medium tracking-wider uppercase">Center</span>
            </div>
        </div>

        {{-- Menu --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            {{-- Dashboard --}}
            <div class="sidebar-group">
                <a href="{{ route('home') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>

            {{-- Employees --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-emp')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('employees*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Employees</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-emp" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-emp" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('employees*') ? 'open' : '' }}">
                    <a href="{{ route('employees.index') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">All Employees</a>
                    <a href="{{ route('employees.create') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Add Employee</a>
                    <a href="{{ route('employees.documents-expiry') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Doc Expiry</a>
                </div>
            </div>

            {{-- Organization --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-org')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('organization*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                    <span>Organization</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-org" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-org" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('organization*') ? 'open' : '' }}">
                    <a href="{{ route('organization.branches') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Branches</a>
                    <a href="{{ route('organization.departments') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Departments</a>
                    <a href="{{ route('organization.positions') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Positions</a>
                    <a href="{{ route('organization.teams') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Teams</a>
                    <a href="{{ route('organization.working-hours') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Working Hours</a>
                    <a href="{{ route('organization.holidays') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Holidays</a>
                    <a href="{{ route('organization.policies') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Policies</a>
                </div>
            </div>

            {{-- Attendance --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-att')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('attendance*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Attendance</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-att" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-att" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('attendance*') ? 'open' : '' }}">
                    <a href="{{ route('attendance.index') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Daily Grid</a>
                    <a href="{{ route('attendance.missing') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Missing</a>
                    <a href="{{ route('attendance.corrections') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Corrections</a>
                    <a href="{{ route('attendance.summary') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Summary</a>
                </div>
            </div>

            {{-- Shifts --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-shift')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('shifts*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Shifts</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-shift" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-shift" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('shifts*') ? 'open' : '' }}">
                    <a href="{{ route('shifts.index') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Catalog</a>
                    <a href="{{ route('shifts.planner') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Planner</a>
                    <a href="{{ route('shifts.rotations') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Rotations</a>
                </div>
            </div>

            {{-- Leave --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-leave')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('leave*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <span>Leave</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-leave" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-leave" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('leave*') ? 'open' : '' }}">
                    <a href="{{ route('leave.types') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Leave Types</a>
                    <a href="{{ route('leave.requests') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Requests</a>
                    <a href="{{ route('leave.balances') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Balances</a>
                </div>
            </div>

            {{-- Payroll --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-pay')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('payroll*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Payroll</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-pay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-pay" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('payroll*') ? 'open' : '' }}">
                    <a href="{{ route('payroll.components') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Components</a>
                    <a href="{{ route('payroll.runs') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Payroll Runs</a>
                    <a href="{{ route('payroll.bonuses') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Bonuses</a>
                    <a href="{{ route('payroll.commissions') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Commissions</a>
                </div>
            </div>

            {{-- Recruitment --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-rec')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('recruitment*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Recruitment</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-rec" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-rec" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('recruitment*') ? 'open' : '' }}">
                    <a href="{{ route('recruitment.jobs') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Job Positions</a>
                    <a href="{{ route('recruitment.applicants') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Applicants</a>
                    <a href="{{ route('recruitment.interviews') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Interviews</a>
                    <a href="{{ route('recruitment.onboarding') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Onboarding</a>
                </div>
            </div>

            {{-- Performance --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-perf')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('performance*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>Performance</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-perf" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-perf" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('performance*') ? 'open' : '' }}">
                    <a href="{{ route('performance.kpis') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">KPIs</a>
                    <a href="{{ route('performance.targets') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Targets</a>
                    <a href="{{ route('performance.evaluations') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Evaluations</a>
                    <a href="{{ route('performance.leaderboard') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Leaderboard</a>
                </div>
            </div>

            {{-- Analytics --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-ana')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('analytics*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Analytics</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-ana" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-ana" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('analytics*') ? 'open' : '' }}">
                    <a href="{{ route('analytics.overview') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Overview</a>
                    <a href="{{ route('analytics.data-entry') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Data Entry</a>
                </div>
            </div>

            {{-- Reports --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-rep')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('reports*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Reports</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-rep" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-rep" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('reports*') ? 'open' : '' }}">
                    <a href="{{ route('reports.employees') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Employees</a>
                    <a href="{{ route('reports.attendance') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Attendance</a>
                    <a href="{{ route('reports.payroll') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Payroll</a>
                    <a href="{{ route('reports.recruitment') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Recruitment</a>
                    <a href="{{ route('reports.performance') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Performance</a>
                    <a href="{{ route('reports.call-center') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Call Center</a>
                    <a href="{{ route('reports.audit') }}" class="block py-1.5 text-xs text-navy-200/70 hover:text-white">Audit Log</a>
                </div>
            </div>

        </div>

        {{-- Bottom User --}}
        <div class="p-4 border-t border-navy-800/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->first_name ? Auth::user()->first_name . ' ' . Auth::user()->last_name : (Auth::user()->name ?? 'User') }}</p>
                    <p class="text-xs text-navy-300/60">{{ ucfirst(Auth::user()->role ?? 'Agent') }}</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('dash-logout').submit();" class="text-navy-300/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="dash-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Search --}}
                <div class="hidden md:flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200 focus-within:border-navy-300 focus-within:ring-2 focus-within:ring-navy-100 transition-all">
                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="globalSearch" placeholder="Search..." class="bg-transparent text-sm outline-none w-48 text-gray-700 placeholder-gray-400">
                </div>

                {{-- Notifications --}}
                <button class="relative p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 animate-fade">
            @yield('content')
        </main>

    </div>

    {{-- SweetAlert2 Alert System --}}
    <script>
    (function() {
        function showAlert(type, title, message) {
            const Swal = window.Swal || window.Sweetalert2;
            if (!Swal) return;
            const colors = {
                success: '#0D3E63',
                error: '#EC2226',
                warning: '#A56035',
                info: '#632871'
            };
            const SwalMixin = Swal.mixin ? Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'swal2-toast' },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            }) : null;
            if (SwalMixin) {
                SwalMixin.fire({
                    icon: type,
                    title: title + (message ? ': ' + message : ''),
                    iconColor: colors[type] || '#0D3E63'
                });
            } else {
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message || '',
                    confirmButtonColor: colors[type] || '#0D3E63',
                    confirmButtonText: 'OK'
                });
            }
        }
        window.showAlert = showAlert;
        window.showToast = showAlert;

        @if(session('status'))
            showAlert('success', 'Success!', '{{ session('status') }}');
        @endif
        @if(session('error'))
            showAlert('error', 'Oops...', '{{ session('error') }}');
        @endif
        @if(session('warning'))
            showAlert('warning', 'Warning', '{{ session('warning') }}');
        @endif
        @if(session('info'))
            showAlert('info', 'Info', '{{ session('info') }}');
        @endif

        @if($errors->any())
            @php $allErrors = $errors->all(); @endphp
            showAlert('error', 'Validation Error', '{{ implode("\n", $allErrors) }}');
        @endif
    })();

    function toggleSidebar() {
        const sidebar = document.getElementById('dashSidebar');
        const overlay = document.getElementById('mobileOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    function toggleMenu(id) {
        const menu = document.getElementById(id);
        const arrow = document.getElementById('arrow-' + id.replace('menu-', ''));
        menu.classList.toggle('open');
        if (arrow) arrow.classList.toggle('rotate-180');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // GLOBAL AJAX + SWEETALERT2 SYSTEM
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    (function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // Toast mixin for quick notifications
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-toast' },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        window.Toast = Toast;

        // Confirm dialog mixin
        const ConfirmMixin = Swal.mixin({
            customClass: {
                popup: 'swal2-popup',
                confirmButton: 'swal2-confirm bg-red-500 hover:bg-red-600 text-white',
                cancelButton: 'swal2-confirm bg-gray-200 hover:bg-gray-300 text-gray-700'
            },
            buttonsStyling: false,
        });
        window.ConfirmMixin = ConfirmMixin;

        // AJAX loader
        function showLoader() {
            let loader = document.getElementById('ajaxProgress');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'ajaxProgress';
                loader.style.cssText = 'position:fixed;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#0D3E63,#A56035,#0D3E63);background-size:200% 100%;animation:ajaxProgress 1s linear infinite;z-index:9999;';
                document.body.appendChild(loader);
                if (!document.getElementById('ajaxProgressStyle')) {
                    const style = document.createElement('style');
                    style.id = 'ajaxProgressStyle';
                    style.textContent = '@keyframes ajaxProgress{0%{background-position:100% 0}100%{background-position:-100% 0}}';
                    document.head.appendChild(style);
                }
            }
            loader.style.display = 'block';
        }
        function hideLoader() {
            const loader = document.getElementById('ajaxProgress');
            if (loader) loader.style.display = 'none';
        }

        // Show toast notification
        window.notify = function(type, title, message) {
            const colors = { success: '#0D3E63', error: '#EC2226', warning: '#A56035', info: '#632871' };
            Toast.fire({
                icon: type,
                title: title + (message ? ': ' + message : ''),
                iconColor: colors[type] || '#0D3E63'
            });
        };

        // Show confirmation dialog
        window.confirmAction = function(options) {
            return Swal.fire({
                title: options.title || 'Are you sure?',
                text: options.text || '',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Yes, delete it!',
                cancelButtonText: options.cancelText || 'Cancel',
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm ' + (options.confirmClass || 'bg-red-500 hover:bg-red-600 text-white'),
                    cancelButton: 'swal2-confirm bg-gray-200 hover:bg-gray-300 text-gray-700'
                },
                buttonsStyling: false,
                reverseButtons: true,
            });
        };

        // Core AJAX function
        window.ajaxRequest = function(url, method, data, options) {
            options = options || {};
            showLoader();

            const headers = {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };

            const fetchOptions = {
                method: method,
                headers: headers,
                credentials: 'same-origin'
            };

            if (data instanceof FormData) {
                fetchOptions.body = data;
                if (method !== 'POST') {
                    data.append('_method', method);
                    fetchOptions.method = 'POST';
                }
            } else if (data) {
                headers['Content-Type'] = 'application/json';
                fetchOptions.body = JSON.stringify(data);
            }

            return fetch(url, fetchOptions)
                .then(r => {
                    const contentType = r.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        return r.json().then(d => ({ data: d, status: r.status, ok: r.ok }));
                    }
                    return r.text().then(html => ({ html, status: r.status, ok: r.ok }));
                })
                .then(result => {
                    hideLoader();
                    if (result.data !== undefined) {
                        if (!result.ok) {
                            if (result.status === 422 && result.data.errors) {
                                const msgs = Object.values(result.data.errors).flat();
                                notify('error', 'Validation Error', msgs.join('. '));
                            } else {
                                notify('error', 'Error', result.data.message || 'Something went wrong');
                            }
                            if (options.onError) options.onError(result.data);
                            return Promise.reject(result.data);
                        }
                        if (result.data.message) {
                            notify(result.data.type || 'success', result.data.title || 'Success', result.data.message);
                        }
                        if (options.onSuccess) options.onSuccess(result.data);
                        if (result.data.redirect) {
                            setTimeout(() => { window.location.href = result.data.redirect; }, 1000);
                        } else if (options.reload !== false) {
                            setTimeout(() => { window.location.reload(); }, 800);
                        }
                        return result.data;
                    }
                    if (result.html && options.onHtml) {
                        options.onHtml(result.html);
                    }
                    return result;
                })
                .catch(err => {
                    hideLoader();
                    if (err && err.message) notify('error', 'Error', err.message);
                    return Promise.reject(err);
                });
        };

        // Intercept all forms with data-ajax or class ajax-form
        function bindAjaxForms() {
            document.querySelectorAll('form[data-ajax], form.ajax-form').forEach(form => {
                if (form._ajaxBound) return;
                form._ajaxBound = true;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    const method = (form.querySelector('input[name="_method"]')?.value || form.method || 'POST').toUpperCase();
                    const isDelete = method === 'DELETE';
                    const confirmMsg = form.dataset.confirm || (isDelete ? 'Delete this item?' : null);

                    function submitForm() {
                        if (btn) { btn.disabled = true; btn.classList.add('btn-loading'); }
                        const formData = new FormData(form);
                        ajaxRequest(form.action, method, formData, {
                            reload: form.dataset.noReload !== 'true',
                            onSuccess: (data) => {
                                if (form.dataset.closeModal) {
                                    const modal = document.getElementById(form.dataset.closeModal);
                                    if (modal) modal.classList.add('hidden');
                                }
                                if (form.dataset.resetOnSuccess === 'true') form.reset();
                            },
                            onError: () => {
                                if (btn) { btn.disabled = false; btn.classList.remove('btn-loading'); }
                            }
                        });
                    }

                    if (confirmMsg) {
                        confirmAction({
                            title: confirmMsg,
                            text: form.dataset.confirmText || '',
                            confirmText: form.dataset.confirmText || 'Yes, delete it!',
                            icon: form.dataset.confirmIcon || 'warning',
                            confirmClass: form.dataset.confirmClass || 'bg-red-500 hover:bg-red-600 text-white',
                        }).then(result => {
                            if (result.isConfirmed) submitForm();
                        });
                    } else {
                        submitForm();
                    }
                });
            });
        }

        // Intercept delete buttons with data-delete-url
        function bindDeleteButtons() {
            document.querySelectorAll('[data-delete-url]').forEach(btn => {
                if (btn._deleteBound) return;
                btn._deleteBound = true;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.dataset.deleteUrl;
                    const confirmMsg = this.dataset.confirm || 'Delete this item?';
                    const confirmText = this.dataset.confirmText || 'Yes, delete it!';
                    const rowId = this.dataset.rowId;

                    confirmAction({
                        title: confirmMsg,
                        text: this.dataset.confirmText || 'This action cannot be undone.',
                        confirmText: confirmText,
                    }).then(result => {
                        if (result.isConfirmed) {
                            ajaxRequest(url, 'DELETE').then(data => {
                                if (rowId) {
                                    const row = document.getElementById(rowId);
                                    if (row) {
                                        row.style.transition = 'opacity 0.3s';
                                        row.style.opacity = '0';
                                        setTimeout(() => row.remove(), 300);
                                    }
                                }
                            });
                        }
                    });
                });
            });
        }

        // Intercept action buttons with data-action-url (for approve, reject, etc.)
        function bindActionButtons() {
            document.querySelectorAll('[data-action-url]').forEach(btn => {
                if (btn._actionBound) return;
                btn._actionBound = true;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.dataset.actionUrl;
                    const method = this.dataset.actionMethod || 'POST';
                    const confirmMsg = this.dataset.confirm;

                    function doAction() {
                        if (btn) { btn.disabled = true; btn.classList.add('btn-loading'); }
                        ajaxRequest(url, method, null, {
                            reload: true,
                            onSuccess: () => {
                                if (btn) { btn.disabled = false; btn.classList.remove('btn-loading'); }
                            },
                            onError: () => {
                                if (btn) { btn.disabled = false; btn.classList.remove('btn-loading'); }
                            }
                        });
                    }

                    if (confirmMsg) {
                        confirmAction({
                            title: confirmMsg,
                            text: this.dataset.confirmText || '',
                            confirmText: this.dataset.confirmText || 'Yes, proceed',
                            confirmClass: this.dataset.confirmClass || 'bg-navy-500 hover:bg-navy-600 text-white',
                            icon: this.dataset.confirmIcon || 'question',
                        }).then(result => {
                            if (result.isConfirmed) doAction();
                        });
                    } else {
                        doAction();
                    }
                });
            });
        }

        // Modal helpers
        window.openModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        };
        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('hidden');
        };

        // Initialize on load and after AJAX content updates
        function initAjax() {
            bindAjaxForms();
            bindDeleteButtons();
            bindActionButtons();
        }

        // Re-bind when DOM changes
        const observer = new MutationObserver(() => initAjax());
        observer.observe(document.body, { childList: true, subtree: true });

        initAjax();
    })();
    </script>
    @stack('scripts')
</body>
</html>

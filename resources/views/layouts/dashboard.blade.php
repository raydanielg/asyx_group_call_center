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

            {{-- Agents --}}
            <div class="sidebar-group">
                <button onclick="toggleMenu('menu-agents')" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('agents*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Agents</span>
                    <svg class="w-4 h-4 ml-auto transition-transform" id="arrow-agents" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="menu-agents" class="sidebar-submenu pl-11 space-y-0.5 {{ request()->routeIs('agents*') ? 'open' : '' }}">
                    <a href="#" class="block py-1.5 text-xs text-navy-200/70 hover:text-white transition-colors">All Agents</a>
                    <a href="#" class="block py-1.5 text-xs text-navy-200/70 hover:text-white transition-colors">Performance</a>
                    <a href="#" class="block py-1.5 text-xs text-navy-200/70 hover:text-white transition-colors">Schedules</a>
                </div>
            </div>

            {{-- Calls --}}
            <div class="sidebar-group">
                <a href="#" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('calls*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>Calls</span>
                </a>
            </div>

            {{-- Reports --}}
            <div class="sidebar-group">
                <a href="#" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('reports*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Reports</span>
                </a>
            </div>

            {{-- Settings --}}
            <div class="sidebar-group">
                <a href="#" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-navy-100 text-sm font-medium {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-copper-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Settings</span>
                </a>
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
    </script>
    @stack('scripts')
</body>
</html>

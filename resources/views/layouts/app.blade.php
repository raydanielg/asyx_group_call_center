<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'AYS Call Center'))</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons8-logo-32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons8-logo-96.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons8-logo-96.png') }}">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <style>
        @keyframes simpleFadeIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes toastIn { from { opacity:0; transform:translateX(100%); } to { opacity:1; transform:translateX(0); } }
        @keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(100%); } }
        .toast-in { animation: toastIn 0.4s cubic-bezier(0.16,1,0.3,1) both; }
        .toast-out { animation: toastOut 0.3s ease-in both; }
        .ajax-loader { position:fixed; top:0; left:0; right:0; height:3px; background: linear-gradient(90deg, #024938, #f9ac00, #024938); background-size: 200% 100%; animation: ajaxProgress 1s linear infinite; z-index:9999; display:none; }
        @keyframes ajaxProgress { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
        .page-transition { animation: simpleFadeIn 0.35s ease-out both; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: { 50:'#e6f5f1',100:'#b3e0d4',200:'#80cbc0',300:'#4db5a8',400:'#1a9f8e',500:'#024938',600:'#023d30',700:'#013028',800:'#01241f',900:'#001816' },
                        gold: { 50:'#fff5e0',100:'#ffe6b3',200:'#ffd680',300:'#ffc64d',400:'#ffb71a',500:'#f9ac00',600:'#d49700',700:'#b07c00',800:'#8c6100',900:'#684600' }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-['Nunito',sans-serif] antialiased text-slate-800 min-h-screen">

    {{-- Auth Background --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('serious-expert-expressing-support-colleague (1).jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 via-emerald-800/85 to-emerald-700/80"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 24px 24px;"></div>
    </div>

    {{-- AJAX Progress Bar --}}
    <div id="ajaxLoader" class="ajax-loader"></div>

    {{-- Toast Container (top right) --}}
    <div id="toastContainer" class="fixed top-5 right-5 z-[60] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>

    <main id="authMain" class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Toast System --}}
    <script>
    (function() {
        const container = document.getElementById('toastContainer');

        function showToast(type, title, message) {
            const toast = document.createElement('div');
            toast.className = 'toast-in pointer-events-auto flex items-start gap-3 p-4 rounded-xl shadow-lg border backdrop-blur-sm';

            let iconSvg, bgClass, borderClass;
            if (type === 'success') {
                iconSvg = '<svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-emerald-50/95';
                borderClass = 'border-emerald-200';
            } else if (type === 'error') {
                iconSvg = '<svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-red-50/95';
                borderClass = 'border-red-200';
            } else if (type === 'warning') {
                iconSvg = '<svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                bgClass = 'bg-amber-50/95';
                borderClass = 'border-amber-200';
            } else {
                iconSvg = '<svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                bgClass = 'bg-blue-50/95';
                borderClass = 'border-blue-200';
            }

            toast.classList.add(...bgClass.split(' '), ...borderClass.split(' '));
            toast.innerHTML = iconSvg +
                '<div class="flex-1 min-w-0">' +
                    '<p class="text-sm font-semibold text-gray-800">' + title + '</p>' +
                    (message ? '<p class="text-sm text-gray-500 mt-0.5">' + message + '</p>' : '') +
                '</div>' +
                '<button onclick="this.parentElement.classList.add(\'toast-out\'); setTimeout(()=>this.parentElement.remove(), 300)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('toast-out');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        window.showToast = showToast;

        @if(session('status'))
            showToast('success', 'Success', '{{ session('status') }}');
        @endif
        @if(session('error'))
            showToast('error', 'Error', '{{ session('error') }}');
        @endif
        @if(session('warning'))
            showToast('warning', 'Warning', '{{ session('warning') }}');
        @endif
        @if(session('info'))
            showToast('info', 'Info', '{{ session('info') }}');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('error', 'Validation Error', '{{ $error }}');
            @endforeach
        @endif
    })();

    // AJAX Navigation System for Auth Pages
    (function() {
        const authMain = document.getElementById('authMain');
        const ajaxLoader = document.getElementById('ajaxLoader');

        function showLoader() { if(ajaxLoader) ajaxLoader.style.display = 'block'; }
        function hideLoader() { if(ajaxLoader) ajaxLoader.style.display = 'none'; }

        function getSk() {
            const url = new URL(window.location.href);
            return url.searchParams.get('sk') || '';
        }

        function loadPage(url, pushState = true) {
            showLoader();
            const sk = getSk();
            let finalUrl = url;
            if (sk) {
                const u = new URL(url, window.location.href);
                u.searchParams.set('sk', sk);
                finalUrl = u.pathname + u.search;
            }
            fetch(finalUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => {
                if (!r.ok) throw new Error('Network error');
                return r.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('main');
                if (newContent && authMain) {
                    authMain.innerHTML = newContent.innerHTML;
                    authMain.classList.remove('page-transition');
                    void authMain.offsetWidth;
                    authMain.classList.add('page-transition');
                    document.title = doc.title;
                    rebindAjaxLinks();
                    rebindForms();
                }
                if (pushState) history.pushState({ url: finalUrl }, '', finalUrl);
                hideLoader();
            })
            .catch(err => {
                hideLoader();
                window.showToast && window.showToast('error', 'Connection Error', 'Please check your internet connection.');
            });
        }

        function rebindAjaxLinks() {
            document.querySelectorAll('a[href]').forEach(function(link) {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('mailto') || href.startsWith('tel') || link.target === '_blank') return;
                const url = new URL(href, location.href);
                if (url.host !== location.host) return;
                if (!url.pathname.match(/\/(login|register|password|verify)/)) return;
                link.removeEventListener('click', handleAjaxClick);
                link.addEventListener('click', handleAjaxClick);
            });
        }

        function handleAjaxClick(e) {
            e.preventDefault();
            loadPage(this.getAttribute('href'));
        }

        function rebindForms() {
            document.querySelectorAll('form[method="POST"]').forEach(function(form) {
                const action = form.getAttribute('action') || form.action || '';
                const url = new URL(action, window.location.href);
                if (url.pathname.match(/\/(login|register|logout|password|email|verification)/)) {
                    return;
                }
                form.removeEventListener('submit', handleAjaxSubmit);
                form.addEventListener('submit', handleAjaxSubmit);
            });
        }

        function handleAjaxSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn ? btn.innerHTML : '';

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';
            }
            showLoader();

            const formData = new FormData(form);
            let actionUrl = form.action;
            const sk = getSk();
            if (sk) {
                const u = new URL(actionUrl, window.location.href);
                u.searchParams.set('sk', sk);
                actionUrl = u.toString();
            }
            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                credentials: 'same-origin',
                redirect: 'follow'
            })
            .then(r => {
                if (r.redirected) {
                    window.location.href = r.url;
                    return null;
                }
                return r.text();
            })
            .then(html => {
                if (html === null) return;
                hideLoader();
                if (btn) { btn.disabled = false; btn.innerHTML = originalHTML; }

                if (html.trim().startsWith('<!DOCTYPE') || html.trim().startsWith('<!doctype')) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('main');
                    if (newContent && authMain) {
                        authMain.innerHTML = newContent.innerHTML;
                        authMain.classList.remove('page-transition');
                        void authMain.offsetWidth;
                        authMain.classList.add('page-transition');
                        document.title = doc.title;
                        rebindAjaxLinks();
                        rebindForms();
                        if (doc.querySelector('meta[name="csrf-token"]')) {
                            const token = doc.querySelector('meta[name="csrf-token"]').content;
                            document.querySelector('meta[name="csrf-token"]').content = token;
                        }
                    } else {
                        window.location.reload();
                    }
                } else {
                    window.location.reload();
                }
            })
            .catch(() => {
                hideLoader();
                if (btn) { btn.disabled = false; btn.innerHTML = originalHTML; }
                window.showToast && window.showToast('error', 'Network Error', 'Please try again.');
            });
        }

        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) loadPage(e.state.url, false);
        });

        rebindAjaxLinks();
        rebindForms();
    })();

    // Append session key to URL for security display
    (function() {
        const currentUrl = new URL(window.location.href);
        if (!currentUrl.searchParams.has('sk')) {
            const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
            let key = '';
            for (let i = 0; i < 36; i++) key += chars.charAt(Math.floor(Math.random() * chars.length));
            currentUrl.searchParams.set('sk', key);
            window.history.replaceState({}, '', currentUrl.toString());
        }
    })();

    </script>

</body>
</html>
